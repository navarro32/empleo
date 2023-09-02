<?php

use Illuminate\Database\Seeder;

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
