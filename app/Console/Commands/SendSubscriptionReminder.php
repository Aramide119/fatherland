<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionReminder;
use App\Models\PromotePostSubscription;

class SendSubscriptionReminder extends Command
{

    protected $signature = 'subscription:send-reminder';
    protected $description = 'Send subscription reminder to users';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
   

    /**
     * The console command description.
     *
     * @var string
     */
    

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
        $subscriptions = PromotePostSubscription::whereDate('end_date', '=', now()->addDays(3)->toDateString())->get();

        foreach ($subscriptions as $subscription) {
            $user = $subscription->user;

            $endDate = Carbon::parse($subscription->end_date);
            $daysLeft = now()->diffInDays($endDate);
            
            Mail::to($user)->send(new SubscriptionReminder($user, $daysLeft));
            $this->info('Reminder sent to ' . $user->email);
        }
    }
}
