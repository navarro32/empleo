<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\User;

Route::get('/', function () {
    // if(Auth::check()){
    //     if(Auth::user()->tipo_user==2){
    //         return redirect()->intended('Empresas');
    //     }elseif(Auth::user()->tipo_user==3){
    //         return redirect()->intended('Usuarios');
    //     }
    // }
    return view('inicio');
});


Route::get('/listado_usuarios', function(){
    return User::select('nombres', 'apellidos', 'email')->get()->toJson();
});

// Auth::routes();
// Authentication Routes...
Route::get('empresas/login', 'Auth\LoginController@showLoginFormEmpresas')->name('login');
Route::get('personas/login', 'Auth\LoginController@showLoginFormPersonas')->name('login2');
Route::post('login', 'Auth\LoginController@login')->name('recibelogin');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
 
// Registration Routes...
Route::get('empresas/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('empresas/register', 'Auth\RegisterController@register')->name('register');

Route::get('personas/register', 'Auth\RegisterController@showRegistrationForm2');
Route::post('personas/register', 'Auth\RegisterController@register')->name('register2');
 
// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
 
// Email Verification Routes...
// Route::emailVerification();


// rutas globales
Route::get('/getAreas', 'HomeController@getAreas')->name('getAreas');
Route::get('/getDepartamentos', 'HomeController@getDepartamentos')->name('getDepartamentos');
Route::post('/getCiudades', 'HomeController@getCiudades')->name('getCiudad');
Route::get('/getSectores', 'HomeController@getSectores')->name('getSectores');
Route::post('/getSubsectores', 'HomeController@getSubsectores')->name('getSubsectores');
Route::post('/buscarCiudad', 'HomeController@buscarCiudad')->name('buscarCiudad');


// rutas para busar empleos

Route::get('/buscar', 'EmpleosController@index')->name('buscar');
Route::get('/oferta/{uuid}', 'EmpleosController@show');
Route::post('/buscar', 'EmpleosController@buscar');
Route::post('/aplicar', 'EmpleosController@aplicar');
// rutas de empresa
Route::middleware(['Empresas'])->group(function () {
    Route::get('/Empresas', 'EmpresasController@index')->name('empresas');
    Route::get('/getEmpresas', 'EmpresasController@getEmpresa');
    Route::post('/editEmpresas', 'EmpresasController@update');
    Route::post('/changePass', 'EmpresasController@updatePass');
    Route::post('/saveOfert', 'EmpresasController@saveOfert');
    Route::post('/editOfert', 'EmpresasController@editOfert');
    Route::get('/getOferts', 'EmpresasController@getOferts');
    Route::get('/getOfert', 'EmpresasController@getOfert');
    Route::delete('/deleteOfert/{id}', 'EmpresasController@deleteOfert');
});


Route::middleware(['Usuarios'])->group(function () {
    Route::get('/Usuarios', 'UsuariosController@index');
    Route::get('/getUser', 'UsuariosController@show');
    Route::get('/getExperience', 'UsuariosController@experience');
    
    Route::post('/editUser', 'UsuariosController@update');
    Route::post('/updateAvatar', 'UsuariosController@updateAvatar');
    Route::post('/saveExpe', 'UsuariosController@saveExpe');  
    Route::post('/uddateExpe', 'UsuariosController@saveExpe'); 
    Route::post('/deleteExp', 'UsuariosController@deleteExp'); 

    Route::get('/getStudys', 'UsuariosController@estudios');
    Route::post('/saveStudy', 'UsuariosController@saveStudy');  
    Route::post('/uddateStudy', 'UsuariosController@saveStudy'); 
    Route::post('/deleteStudy', 'UsuariosController@deleteStudy'); 
    Route::post('/savePerfil', 'UsuariosController@savePerfil');  
});



