<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Router;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Router::class, function (Faker $faker) {
    return [
        'sap_id'        => Str::random(18),
        'hostname'      => mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255),
        'mac_address'   => implode(':', str_split(str_pad(base_convert(mt_rand(0, 0xffffff), 10, 16) . base_convert(mt_rand(0, 0xffffff), 10, 16), 12), 2))
    ];
});
