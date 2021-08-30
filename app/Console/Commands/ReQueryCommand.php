<?php

namespace App\Console\Commands;

use App\Classes\Capricorn;
use App\Classes\ETranzact;
use App\Classes\Shago;
use App\ReQuery;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReQueryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:re-query';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to re-query all pending transactions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $queries = ReQuery::all();
        if (count($queries) > 0){
            foreach ($queries as $que){
                if ($que->status == 'pending'){
                        //Shago
                        if ($que->provider == 'Shago'){
                            $transaction = $que->transaction;
                            Log::info('===Shago Query Running===');
                            $response = Shago::re_query($transaction->reference);
                            if ($response['success'] && $response['status'] == 'success')
                                {
                                    $transaction->update([
                                        'status' => 'SUCCESSFUL'
                                    ]);
                                    $que->status = 'success';
                                    $que->save();
                                    Log::info('===Updated A Shago Transaction===');
                                }
                            if ($response['success'] == false && $response['status'] == 'failed')
                            {
                                $transaction->update([
                                    'status' => 'FAILED'
                                ]);
                                $que->status = 'failed';
                                $que->save();
                                Log::info('===Updated A Shago Transaction===');
                            }
                        }

                        //Capricorn
                        if ($que->provider == 'Capricorn'){
                            $transaction = $que->transaction;
                            Log::info('===Capricorn Query Running===');
                            $response = Capricorn::reQuery($transaction->reference);
                                if ($response['success'] && $response['status'] == 'success')
                                {
                                    $transaction->update([
                                        'status' => 'SUCCESSFUL'
                                    ]);
                                    $que->status = 'success';
                                    $que->save();
                                    Log::info('===Updated A Capricorn Transaction===');
                                }
                                if ($response['success'] && $response['status'] == 'error')
                                {
                                    $transaction->update([
                                        'status' => 'FAILED'
                                    ]);
                                    $que->status = 'failed';
                                    $que->save();
                                    Log::info('===Updated A Capricorn Transaction===');
                                }
                        }

                        //ETranzact
                        if ($que->provider == 'ETranzact'){
                            $transaction = $que->transaction;
                            Log::info('===ETranzact Query Running===');
                            $response = ETranzact::getTransaction($transaction->reference);
                            if ($response['success'] && $response['status'] == 'success')
                            {
                                $transaction->update([
                                    'status' => 'SUCCESSFUL'
                                ]);
                                $que->status = 'success';
                                $que->save();
                                Log::info('===Updated A ETranzact Transaction===');
                            }

                            if ($response['success'] == false)
                            {
                                $transaction->update([
                                    'status' => 'FAILED'
                                ]);
                                $que->status = 'failed';
                                $que->save();
                                Log::info('===Updated A ETranzact Transaction===');
                            }
                        }
                }
            }
        }
        else{
            Log::info('===No Re-query at the moment===');
        }
    }
}
