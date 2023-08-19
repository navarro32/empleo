<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Empresa;
use Faker\Generator as Faker;

// factory(App\User::class, 30)->create()->each(function($user) {
//    if($user->tipo_user==2){
//     $address = factory(Empresa::class)->create([
//         'user_id' => $user->id
//     ]);
//    }    
// });


$factory->define(Empresa::class, function (Faker $faker) {
    return [
        'user_id'       =>  $faker->unique()->numberBetween(1, App\User::count()),
        'razon_social'  =>  $faker->company,
        'nit'           =>  $faker->unique()->numberBetween(100000000, 999999999),
        'direccion'     =>  $faker->address,
        'telefono'      =>  $faker->numerify('########'),
        'url'           =>  $faker->url,
        'logo'           => $faker->imageUrl(),
        'descripcion'   => '<div><b>' . $faker->sentence(3) . '</b>' . $faker->sentence(5) .  '</div><div>' . $faker->text(250) . '</div>'
    ];
});
