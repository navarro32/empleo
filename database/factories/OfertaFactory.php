<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Oferta;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Oferta::class, function (Faker $faker) {
    return [
        'titulo'        => $faker->jobTitle,
        'sueldo'        => $faker->biasedNumberBetween(1000000, 5000000),
        'ciudad_id'     => App\Models\Ciudades::all()->random()->id,
        'area_id'       => App\Models\Area::all()->random()->id,
        'user_id'       => App\User::where('tipo_user', 2)->get()->random()->id,
        'vacantes'      => $faker->numberBetween(1, 10),
        'descripcion'   => '<div><b>' . $faker->sentence(3) . '</b>' . $faker->sentence(5) .  '</div><div>' . $faker->text(250) . '</div>',
        'experiencia'   => $faker->randomNumber(2, false),
        'contrato'      => $faker->numberBetween(1, 4),
        'estado'        => 1,
        'uuid'          => Str::uuid(),
    ];
});
