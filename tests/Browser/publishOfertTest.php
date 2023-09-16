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
                ->visit(url('/') . '/Empresas#/nuevaOferta')
                ->waitFor('@menu')
                ->type('#oferta', 'Oferta de pruebas Selenium 56 ')
                ->type('#sueldo', '3000000');
            $browser->script('$("#area_hidden").css({"left":"0"});');
            $browser->script('$("#departamento_hidden").css({"left":"0"});');
            $browser->script('$("#ciudad_hidden").css({"left":"0"});');
            $browser->script('$("#tipo_fecha_hidden").css({"left":"0"});');
            $browser->script('$("#tipo_contrato_hidden").css({"left":"0"});');
            $browser->script('$("#texto_textarea_hidden").css({"left":"0"});');
            $browser->type('#area_hidden', '2')
                ->type('#departamento_hidden', '2')
                ->pause(2000)
                ->type('#ciudad_hidden', '2')
                ->type('#vacante', '2')
                ->type('#experiencia', 2)
                ->type('#tipo_fecha_hidden', '2')
                ->type('#tipo_contrato_hidden', '2')
                ->type('#texto_textarea_hidden', 'Prueba de oferta')                
                ->press('#guardar');
                $browser->script('console.log("################ Se envio correctamente el formulario ###################");');
            ;
        });
    }
}
