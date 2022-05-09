<?php

namespace App\Imports\Processors;

use App\Imports\UsersPaymentImport;
use App\Models\Album;
use App\Models\Import;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\View\View;
use Rap2hpoutre\FastExcel\FastExcel;

class OrchardReportProcessor extends BaseProcessor
{
    const SINGO_AMOUNT_DEDUCTION_RATE = 20;

    public $summary = [];
    public $unassignedUpc = [];

    function process(): BaseProcessor
    {
        $depositableEarnings = 0;

        $unassignedUpc = [];

        if ($this->import->type == Import::IMPORT_TYPE_USERS_PAYMENT_REPORT_FUGA) {

            $rows = (new FastExcel)->import(storage_path('app/' . $this->import->filepath), function ($row) {
                $upc = str_pad((int) $row['Product UPC'], 12, 0);
                $retailer = Str::slug($row['DSP']);
                $artistName = Str::slug($row['Product Artist']);
                $rate = ($this->import->rate != 0) ? $this->import->rate : 1;

                return [
                    'Product UPC'              => $upc,
                    'Product Artist'           => $row['Product Artist'],
                    'DSP'                      => $row['DSP'],
                    'Label Share Net Receipts' => ((float) str_replace(',', '', $row['Reported Royalty'])) / $rate,
                    'Quantity'                 => (int) str_replace(',', '', $row['Asset Quantity']),
                    'artist_filter_key'        => "{$upc}_{$artistName}",
                    'dsp_filter_key'           => $retailer,
                ];
            });

            // dd(get_class($rows));

            $this->data = $rows->groupBy('artist_filter_key')
                ->map(function ($artist) {
                    return $artist->groupBy('dsp_filter_key')
                        ->map(function ($group, $retailer) {
                            $artistTotalEarnings = $group->sum('Label Share Net Receipts');
                            $artistTotalEarningsAfterDeduction = $artistTotalEarnings - (($artistTotalEarnings * self::SINGO_AMOUNT_DEDUCTION_RATE) / 100);

                            return [
                                'upc'                           => $group->first()['Product UPC'],
                                'dsp'                           => $group->first()['DSP'],
                                'retailer'                      => $retailer,
                                'artist_name'                   => $group->first()['Product Artist'],
                                'total_streams'                 => $group->sum('Quantity'),
                                'total_earning'                 => $artistTotalEarnings,
                                'total_earning_after_deduction' => $artistTotalEarningsAfterDeduction,
                            ];
                        });
                })
                ->filter(function ($artist, $upcArtistKey) use (&$unassignedUpc, &$depositableEarnings) {
                    $upc = Str::before($upcArtistKey, '_');

                    $upcExistsInDb = DB::table('albums')->where('upc', $upc)->exists();

                    if (!$upcExistsInDb) {
                        $unassignedUpc[] = $artist;
                    } else {
                        $depositableEarnings += $artist->sum('total_earning');
                    }

                    return $upcExistsInDb;
                });
        } else {
            $collection = (new FastExcel)->import(storage_path('app/' . $this->import->filepath));
            $rows = $collection->map(function ($row) {
                $upc = str_pad((int) $row['Orchard UPC'], 12, 0);

                $retailer = Str::slug($row['Retailer']);
                $artistName = Str::slug($row['Artist Name']);
                $rate = ($this->import->rate != 0) ? $this->import->rate : 1;

                return [
                    'Orchard UPC'              => $upc,
                    'Retailer'                 => $row['Retailer'],
                    'Artist Name'              => $row['Artist Name'],
                    'Label Share Net Receipts' => ((float) str_replace(',', '', $row['Label Share Net Receipts'])) / $rate,
                    'Quantity'                 => (int) str_replace(',', '', $row['Quantity']),
                    'artist_filter_key'        => "{$upc}_{$artistName}",
                    'dsp_filter_key'           => "{$retailer}",
                ];
            }); // Select first sheet data

            $this->data = $rows->groupBy('artist_filter_key')
                ->map(function ($artist) {
                    return $artist->groupBy('dsp_filter_key')
                        ->map(function ($group, $retailer) {
                            $artistTotalEarnings = $group->sum('Label Share Net Receipts');
                            $artistTotalEarningsAfterDeduction = $artistTotalEarnings - (($artistTotalEarnings * self::SINGO_AMOUNT_DEDUCTION_RATE) / 100);

                            return [
                                'upc'                           => $group->first()['Orchard UPC'],
                                'dsp'                           => $group->first()['Retailer'],
                                'retailer'                      => $retailer,
                                'artist_name'                   => $group->first()['Artist Name'],
                                'total_streams'                 => $group->sum('Quantity'),
                                'total_earning'                 => $artistTotalEarnings,
                                'total_earning_after_deduction' => $artistTotalEarningsAfterDeduction,
                            ];
                        });
                })
                ->filter(function ($artist, $upcArtistKey) use (&$unassignedUpc, &$depositableEarnings) {
                    $upc = Str::before($upcArtistKey, '_');

                    $upcExistsInDb = DB::table('albums')->where('upc', $upc)->exists();

                    if (!$upcExistsInDb) {
                        $unassignedUpc[] = $artist;
                    } else {
                        $depositableEarnings += $artist->sum('total_earning');
                    }

                    return $upcExistsInDb;
                });
        }

        $totalEarnings = $rows->sum('Label Share Net Receipts');
        $deductedAmount = ($depositableEarnings * self::SINGO_AMOUNT_DEDUCTION_RATE) / 100;
        $totalEarningsAfterDeduction = $depositableEarnings - $deductedAmount;

        $unassignedUpcEarnings = ($totalEarnings - $depositableEarnings);
        $leftForSingo = $deductedAmount + $unassignedUpcEarnings;

        $this->summary = [
            'total_streams'                  => number_format($rows->sum('Quantity')),
            'total_earnings'                 => number_format($totalEarnings, 2),
            'deducted_amount'                => number_format($deductedAmount, 2),
            'left_for_singo'                 => number_format($leftForSingo, 2),
            'depositable_total_earnings'     => number_format($depositableEarnings, 2),
            'total_earnings_after_deduction' => number_format($totalEarningsAfterDeduction, 2),
            'unassigned_upc_earnings'        => number_format($unassignedUpcEarnings, 2),
        ];
        $this->missingUpc = $unassignedUpc;

        return $this;
    }

    function render(): View
    {
        return view('admin.imports.processed.users-payment-report', [
            'import'      => $this->import,
            'artists'     => $this->data,
            'missing_upc' => $this->missingUpc,
            'summary'     => $this->summary
        ]);
    }
}
