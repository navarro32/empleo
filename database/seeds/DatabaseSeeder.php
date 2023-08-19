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
    }
}
