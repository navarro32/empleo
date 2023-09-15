<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;

class publishOfertTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
       
            // $user = factory(User::class)->create([
            //     'email' => 'taylor@laravel.com',
            // ]);     
            
            
            // {"nombres":"pruebas","sueldo":"2000000","area":4,"departamento":11,"ciudades":149,"vacantes":"2","experiencia":"2","tipoFecha":2,"contrato":2,"descripcion":"<p>Pruebas</p>"}
            $user = User::where('tipo_user', 2)->first();
            $this->browse(function ($browser) use ($user) {

               

                $selector = "//div[id='div_areas']//input[type='hidden']";
                $browser->visit('/empresas/login')
                        ->type('email', $user->email)
                        ->type('password', 'password')
                        ->press('Ingresar')
                        ->assertPathIs('/Empresas')                        
                        ->visit(url('/').'/Empresas#/nuevaOferta')
                        ->waitFor('@menu')
                        ->type('#oferta', 'Oferta de pruebas Selenium')
                        ->type('#sueldo', '2000000')                        
                        ->pause(3000)
                        ->waitFor('#div_areas .v-select__slot')
                        ->script('document.querySelector("#area").value = "3";')
                        ->type('#departamento', 'Bogotá D.C.')
                        ->type('#ciudad', 'Bogotá D.C.')
                        ->type('#vacante', '2')
                        ->value('#experiencia', '2')
                        ->value('#tipo_fecha', 'años')
                        ->value('#tipo_contrato', 'Término indefinido')
                        ->value('#texto_textarea', 'Prueba de oferta')
                        ->pause(3000)
                        ->press('#guardar');
                        // ->assertVue('datosP', "razon_social", '@menu')
                        ;
            });          

       
       
    }
}
