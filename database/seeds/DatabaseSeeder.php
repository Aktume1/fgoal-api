<?php

use Illuminate\Database\Seeder;
use App\Eloquent\Group;
use App\Eloquent\User;
use App\Eloquent\Quarter;
use App\Eloquent\Unit;
use App\Eloquent\Objective;
use App\Eloquent\Tracking;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::query()->truncate();
        Group::query()->truncate();
        Quarter::query()->truncate();
        Unit::query()->truncate();
        Objective::query()->truncate();
        Group::query()->truncate();
        Tracking::query()->truncate();

        $this->call(UsersTableSeeder::class);
        $this->call(WorkspacesTableSeeder::class);
        $this->call(QuartersTableSeeder::class);
        $this->call(UnitsTableSeeder::class);
        $this->call(GroupsTableSeeder::class);
        $this->call(TrackingsTableSeeder::class);
    }
}
