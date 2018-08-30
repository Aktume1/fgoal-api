<?php

use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('units')->insert([
            'unit' => '%',
        ]);

        DB::table('units')->insert([
            'unit' => 'kg',
        ]);

        DB::table('units')->insert([
            'unit' => 'g',
        ]);

        DB::table('units')->insert([
            'unit' => 'vnd',
        ]);
    }
}