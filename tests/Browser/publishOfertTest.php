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
        // {"nombres":"pruebas","sueldo":"2000000","area":4,"departamento":11,"ciudades":149,"vacantes":"2","experiencia":"2","tipoFecha":2,"contrato":2,"descripcion":"<p>Pruebas</p>"}
        $user = User::where('tipo_user', 2)->first();
        $this->browse(function ($browser) use ($user) {
            
            $browser->visit('/empresas/login')  #INGRESAR AL ENLACE https://aes-empleo.navarro.com.co/empresas/login
                ->type('email', $user->email)   # INGRESA UN USUARIO OBTENIDO ANTERIORMENTE CON ROL DE EMPRESA
                ->type('password', 'password')  # INGRESA LA CONTRASEÑA POR DEFECTO
                ->press('Ingresar')             # PRESIONA EL BOTON DE INGRESAR    
                ->assertPathIs('/Empresas')     # INGRESA A LA SUBPAGINA https://aes-empleo.navarro.com.co/Empresas#/
                ->visit(url('/') . '/Empresas#/nuevaOferta') # INGRESA A LA SUBPAGINA DEL FORMULARIO DE CREACION https://aes-empleo.navarro.com.co/Empresas#/nuevaOferta
                ->waitFor('@menu')              # ESPERA A QUE CARGUE EL VUE JS
                ->type('#oferta', 'Oferta de pruebas Selenium 56 ') # INSERTA EL TITULO DE LA OFERTA
                ->type('#sueldo', '3000000');                       # INSERTA EL SUELDO    

                # HABILITA LOS CAMPOS OCULTOS PARA ENVIAR DATOS DE LOS SELECT 
            $browser->script('$("#area_hidden").css({"left":"0"});'); 
            $browser->script('$("#departamento_hidden").css({"left":"0"});');
            $browser->script('$("#ciudad_hidden").css({"left":"0"});');
            $browser->script('$("#tipo_fecha_hidden").css({"left":"0"});');
            $browser->script('$("#tipo_contrato_hidden").css({"left":"0"});');
            $browser->script('$("#texto_textarea_hidden").css({"left":"0"});');


            $browser->type('#area_hidden', '2') # ASIGNA EL ID DE UN AREA
                ->type('#departamento_hidden', '2') #ASIGNA EL ID DE UN DEPARTAMENTO
                ->pause(2000)                       #ESPERA UNOS SEGUNDOS PARA CARGAR LAS CIUDADES
                ->type('#ciudad_hidden', '2')       # ASIGNA EL ID DE UNA CIUDAD
                ->type('#vacante', '2')             # ASIGNA LA CANTIDAD DE VACANTES
                ->type('#experiencia', 2)           # ASIGNA LA EXPERIENCIA
                ->type('#tipo_fecha_hidden', '2')   # ASIGNA EL TIPO DE EXPERIENCIA
                ->type('#tipo_contrato_hidden', '2')# ASIGNA EL TIPO DE CONTRATO
                ->type('#texto_textarea_hidden', 'Prueba de oferta')    # AGREGA UNA DESCRIPCION DE PRUEBAS
                ->press('#guardar');                # PRESIONA EL BOTON DE GUARDAR
            $browser->script('console.log("################ Se envio correctamente el formulario ###################");');; # IMPRIME EN CONSOLA 
        });
    }
}
