<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombres', 'apellidos', 'email', 'tipo_user', 'estado', 'email_verified_at', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'id', 'email_verified_at', 
        'deleted_at', 'created_at', 'updated_at', 
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $dates = ['deleted_at']; //Registramos la nueva columna

    public function empresas(){
        return $this->hasOne('App\Models\Empresa');
    }

    public function perfil(){
        return $this->hasOne('App\Models\Perfil');
    }
    public function datosBasicos(){
        return $this->hasOne('App\Models\DatosBasicos');
    }
    public function experiencia(){
        return $this->hasMany('App\Models\Experiencia')
        ->orderBy('trabajo_aqui', 'DESC')
        ->orderBy('fecha_fin', 'DESC');
    }

    public function estudios(){
        return $this->hasMany('App\Models\Estudio')        
        ->orderBy('fecha_fin', 'DESC');
    }

}
