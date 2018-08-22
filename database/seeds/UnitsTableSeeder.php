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
            'name' => '%',
        ]);

        DB::table('units')->insert([
            'name' => 'kg',
        ]);

        DB::table('units')->insert([
            'name' => 'g',
        ]);

        DB::table('units')->insert([
            'name' => 'vnd',
        ]);
    }
}
