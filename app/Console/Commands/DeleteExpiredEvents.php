<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class DeleteExpiredEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:delete-expired';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired events';

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
        // Delete events where the date has passed
        Event::where('end_date', '<', now()->toDateString())->delete();

        $this->info('Expired events deleted successfully.');
    }
}
