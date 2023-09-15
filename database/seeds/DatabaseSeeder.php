<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Areas::class);
        $this->call(Departamentos::class);
        $this->call(CiudadesSeeder::class);
        $this->call(Sectores::class);
        $this->call(SubSectores::class);

        // $user1 = factory(User::class)->create([
        //     'nombres' => "Pruebas de",
        //     'apellidos' => "Empresa",
        //     'email' => "navarroal@javeriana.edu.co",
        //     'tipo_user' => 2,
        //     'estado' => 1,
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        // ]);

        // $address = factory(App\Models\Empresa::class)->create([
        //     'user_id' => $user1->id
        // ]);

        // $user2 = factory(User::class)->create([
        //     'nombres' => "Pruebas de",
        //     'apellidos' => "Cliente",
        //     'email' => "gueteju@javeriana.edu.co",
        //     'tipo_user' => 3,
        //     'estado' => 1,
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        // ]);

        // $address = factory(App\Models\Empresa::class)->create([
        //     'user_id' => $user2->id
        // ]);

        factory(App\User::class, 20)->create()->each(function ($user) {
            if ($user->tipo_user == 2) {
                $address = factory(App\Models\Empresa::class)->create([
                    'user_id' => $user->id
                ]);
            } else if ($user->tipo_user == 3) {
                $address = factory(App\Models\DatosBasicos::class)->create([
                    'user_id' => $user->id
                ]);
            }
        });

        factory(App\Models\Oferta::class, 500)->create();
    }
}
