<?php

namespace App\Jobs;

use App\Exports\WalletDepositExport;
use App\Imports\Processors\BaseProcessor;
use App\Models\Album;
use App\Models\Import;
use App\Models\Report;
use App\Models\Store;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class DepositUserWalletJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;

    /**
     * @var Import
     */
    private $import;

    /**
     * @var BaseProcessor
     */
    private $processor;

    /**
     * @var User
     */
    private $reportSaver;

    /**
     * Create a new job instance.
     *
     * @param Import $import
     * @param BaseProcessor $processor
     * @param User $reportSaver
     */
    public function __construct(Import $import, BaseProcessor $processor, User $reportSaver)
    {
        $this->import = $import;
        $this->processor = $processor;
        $this->reportSaver = $reportSaver;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $albums = $this->processor->process()->getData();

        $userReports = [];

        foreach ($albums as $albumDSPs) {
            $first = $albumDSPs->first();

            $album = Album::has('user')->where('upc', $first['upc'])->with('user')->first();
            $artist = $album->user;

            if (is_null($artist)) {
                dump("Skipping. Not found in DB.");
                continue;
            }

            foreach ($albumDSPs as $dsp) {
                $store = Store::firstOrCreate(
                    ['title' => $dsp['dsp']],
                    ['created_by' => $this->reportSaver->id]
                );

                $userReports[$artist->id][] = [
                    'upc' => $first['upc'],
                    'date' => date('Y-m-d'),
                    'streams' => $dsp['total_streams'],
                    'money' => $dsp['total_earning_after_deduction'],
                    'import_id' => $this->import->id,
                    'store_id' => $store->id,
                    'user_id' => $artist->id,
                    'created_by' => $this->reportSaver->id,
                    'created_at' => now()->toDateTimeString(),
                ];
            }
        }

        $depositLog = [];

        foreach ($userReports as $userId => $reports) {
            try {
                DB::beginTransaction();

                // TODO: Create reports
                Report::insert($reports);

                // TODO: Deposit total amount to user wallet
                $amount = collect($reports)->sum('money');

                $user = User::find($userId);

                $log = [
                    'user_id' => $userId,
                    'current_balance' => $user->balance,
                    'deposit_amount' => $amount
                ];

                $user->balance = ($user->balance + $amount);
                $user->save();

                DB::commit();

                $depositLog[] = $log;
            } catch (\Exception $exception) {
                DB::rollBack();
                Log::error("DepositUserWalletJob: Failed to deposit user account {$userId} - " . $exception->getMessage());
            }
        }

        $logFilePath = 'exports/' . uniqid() . '.csv';
        Excel::store(new WalletDepositExport($depositLog), $logFilePath);

        $this->import->update([
            'log_filepath' => $logFilePath,
            'status' => Import::IMPORT_STATUS_PROCESSED,
        ]);
    }

}
