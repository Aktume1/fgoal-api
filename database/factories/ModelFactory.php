<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Eloquent\User::class, function (Faker\Generator $faker) {
    static $levelIds;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '123456',
        'code' => $faker->isbn10,
        'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'gender' => rand(0, 2),
        'phone' => $faker->phoneNumber,
        'mission' => $faker->name,
        'group_id' => rand(0, 20),
        'manager_id' => rand(0, 20),
        'localtion' => $faker->cityPrefix,
        'avatar' => $faker->imageUrl($width = 640, $height = 480),
        'token_verification' => bcrypt(str_random(5)),
        'status' => $faker->randomElement(config('model.user.status')),
    ];
});
