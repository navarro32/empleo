<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Models\Empresa;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorEmpresa(array $data)
    {
        return Validator::make($data, [
            'razon_social' => ['required', 'string', 'max:255'],
            'nit' => ['required', 'string', 'max:20', 'unique:empresas'],
            'direccion' => ['required', 'string',  'max:255'],
            'telefono' => ['required', 'string', 'max:20'],
            'url' => ['required', 'string', 'max:255'],
            'logo' => ['image', 'mimes:jpeg,jpg,png', 'required', 'max:700'],
        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'nombres'   => $data['nombre'],
            'apellidos' => $data['apellido'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'tipo_user' => $data['tipo'],
            'estado'    => 1,
        ]);
    }
     /**
     * El formulario de registro de las empresas
     *    
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
     /**
     * El formulario de registro de los usuarios
     *    
     */
    public function showRegistrationForm2()
    {
        return view('auth.register2');
    }  

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        if ($request->getPathInfo() == '/empresas/register') {            
            $validator = $this->validatorEmpresa($request->all());
            if ($validator->fails()) {
                return redirect('empresas/register')
                    ->withErrors($validator)
                    ->withInput();
            }     
            $user->tipo_user=2;
            $user->update();     

            // relacion de empresas
            $empresas = new Empresa;
            $empresas->razon_social   = $request->razon_social;
            $empresas->nit            = $request->nit;
            $empresas->user_id        = $user->id;
            $empresas->direccion      = $request->direccion;
            $empresas->telefono       = $request->telefono;
            $empresas->url            = $request->url;
            $empresas->descripcion    = $request->descripcion;
            $path = $request->file('logo')->store(
                'logos/' . $user->id,
                'public'
            );
            $empresas->logo            = $path;
            $empresas->save();
            return redirect()->intended('Empresas');
        }else{
            $user->tipo_user=3;
            $user->update();   
            return redirect()->intended('Usuarios');
        }
    }
}
