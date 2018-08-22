<?php


use Illuminate\Database\Seeder;
use App\Eloquent\Unit;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Unit
        factory(Unit::class, 5)->create();
        sleep(2);
        factory(Unit::class)->create([
            'unit' => '%',
        ]);

	}
}
