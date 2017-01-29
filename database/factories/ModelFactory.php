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

$factory->define(\TB\User\Entities\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'first_name' => $faker->name,
        'last_name' => $faker->name,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'verified' => 1,
        'status_id' => 1,
        'profile_image' => ''
    ];
});
