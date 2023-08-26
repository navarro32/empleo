<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\DatosBasicos;
use App\Models\Departamento;
use Carbon\Carbon;
use Faker\Generator as Faker;


$factory->define(DatosBasicos::class, function (Faker $faker) {
    $dep=App\Models\Departamento::all()->random();   
    
    return [    
        'telefono'              =>  $faker->numerify('########'),  
        'celular'               =>  $faker->numerify('###########'),    
        'documento'             =>  $faker->numerify('###########'),
        'fecha_nacimiento'      =>  $faker->date(),
        'genero'                =>  $faker->randomElement(['F', 'M']),
        'estado_civil'          =>  $faker->randomElement(['Soltero', 'union_libre', 'Casado', 'Divorsiado', 'viudo']),
        'departamento'          =>  $dep->id,
        'ciudad'                =>  $dep->ciudades->random()->id,
        'direccion'             =>  $faker->address,
        'ruta_avatar'           => url('/')."/images/default.jpg", // $faker->imageUrl(),
        'created_at'            => Carbon::now()
    ];
});
