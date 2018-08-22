<?php

use Illuminate\Database\Seeder;

class QuartersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quarters')->insert([
            'start_date' => '2018-01-01',
            'end_date' => '2018-03-31',
        ]);

        DB::table('quarters')->insert([
            'start_date' => '2018-04-01',
            'end_date' => '2018-06-30',
        ]);

        DB::table('quarters')->insert([
            'start_date' => '2018-07-01',
            'end_date' => '2018-09-30',
        ]);

        DB::table('quarters')->insert([
            'start_date' => '2018-09-01',
            'end_date' => '2018-12-31',
        ]);

    }
}
