<?php

namespace App\Console\Commands;

use App\Model\Business;
use App\Notifications\AllNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class NotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send all automated notification';

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
        //Get all businesses
        $businesses = Business::all();

        //Loop through each
        foreach ($businesses as $business){
            if ( !empty($business->alert_balance) && $business->wallet->balance < $business->alert_balance ){
                //Send Notifications
                $details = [
                    'subject' => 'Low Balance Notification 🔊',
                    'greeting' => 'Hello 👋🏾! '. $business->name ,
                    'body' => "You wallet balance is below NGN {$business->alert_balance}, top up now using the button below",
                    'moreBody' => "Current Wallet Balance: NGN {$business->wallet->balance}",
                    'thanks' => 'Sagecloud Automated Notification - You can disable from your settings page.',
                    'actionText' => 'TopUp Now!',
                    'actionURL' => url('/merchant/wallet/view')
                ];

                // Fetch team members
                $teamMembers = $business->users;
                foreach ($teamMembers as $teamMember){
                    Notification::route('mail',$teamMember->email)->notify(new AllNotification($details));
                }
            }
        }
    }
}
