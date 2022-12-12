<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersPaymentImport implements ToCollection, WithHeadingRow, WithMapping
{
    /**
     * @param  Collection  $collection
     */
    public function collection(Collection $collection)
    {
        //
    }

    public function map($row): array
    {
        $upc = str_pad((string) $row['orchard_upc'], 12, 0);
        $retailer = Str::slug($row['retailer']);
        $artistName = Str::slug($row['artist_name']);

        return collect($row)->merge([
            'orchard_upc'              => $upc,
            'label_share_net_receipts' => ((float) str_replace(',', '', $row['label_share_net_receipts'])) / 6.54,
            'quantity'                 => (int) str_replace(',', '', $row['quantity']),
            'filter_key'               => "{$upc}_{$artistName}_{$retailer}",
            'artist_filter_key'        => "{$upc}_{$artistName}",
            'dsp_filter_key'           => "{$retailer}",
        ])->toArray();
    }
}
