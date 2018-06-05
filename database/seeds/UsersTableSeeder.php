<?php

use App\Eloquent\User;
use App\Eloquent\Service;
use App\Eloquent\Category;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        factory(User::class, 100)->create();

        User::find(1)->update([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'status' => config('model.user.status.active'),
        ]);
    }
}
