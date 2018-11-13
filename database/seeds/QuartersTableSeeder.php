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
            'name' => 'Quarter 1',
            'start_date' => '2018-01-01',
            'end_date' => '2018-03-31',
            'expried' => 1,
        ]);

        DB::table('quarters')->insert([
            'name' => 'Quarter 2',
            'start_date' => '2018-04-01',
            'end_date' => '2018-06-30',
            'expried' => 1,
        ]);

        DB::table('quarters')->insert([
            'name' => 'Quarter 3',
            'start_date' => '2018-07-01',
            'end_date' => '2018-09-30',
            'expried' => 1,
        ]);

        DB::table('quarters')->insert([
            'name' => 'Quarter 4',
            'start_date' => '2018-10-01',
            'end_date' => '2018-12-31',
            'expried' => 0,
        ]);
    }
}
