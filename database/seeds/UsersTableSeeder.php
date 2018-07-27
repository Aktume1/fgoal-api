<?php
use App\Eloquent\User;
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
        factory(User::class, 100)->create();
        User::find(1)->update([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'status' => config('model.user.status.active'),
        ]);
    }
}