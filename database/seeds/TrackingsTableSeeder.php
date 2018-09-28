<?php

use Illuminate\Database\Seeder;

class TrackingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($n = 0; $n < 4; $n++) {
            for ($i = 0; $i < 12; $i++) {
                DB::table('trackings')->insert([
                    'quarter_id' => $n + 1,
                    'week' => $i + 1,
                ]);
            }
        }
    }
}
