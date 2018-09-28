<?php

namespace App\Console\Commands;

use App\Repositories\GroupRepositoryEloquent;
use Illuminate\Console\Command;

class TrackingProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TrackingProgress:tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tracking progress of user every week';

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
     * @return mixed
     */
    public function handle(GroupRepositoryEloquent $eloquent)
    {
        $eloquent->trackingByWeek();
    }
}
