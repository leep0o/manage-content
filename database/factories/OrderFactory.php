<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'firstname' => $faker->firstName(),
        'secondname' => $faker->lastName(),
        'email' => $faker->email(),
        'phone' => $faker->phoneNumber(),
        'education' => $faker->numberBetween(1, count(Order::educationsList())),
        'ip' => $faker->ipv4(),
        'agent' => $faker->userAgent(),
        'user_id' => User::inRandomOrder()->first()->id,
    ];
});
