<?php

namespace App\Jobs;

use App\Classes\Shago;
use App\Model\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateTransactionStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * Create a new job instance.
     *
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pending_transactions = $this->transaction
            ->where('status', 'PENDING')->get();
        foreach ($pending_transactions as $transaction) {
            $response = Shago::re_query($transaction->reference);
            if ($response['success'] && $response['status'] == 'success') {
                $transaction->update([
                    'status' => 'SUCCESSFUL'
                ]);
            }
        }
    }
}
