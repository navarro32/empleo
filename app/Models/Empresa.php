<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

class Empresa extends Model
{  

    protected $table = 'empresas';

    protected $fillable = [
        'user_id', 'razon_social', 'nit', 'direccion', 'telefono', 'url', 'logo', 'descripcion'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    public function getLogoAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL) === FALSE) {
            return asset(Storage::url($value));
        }else{
            return $value;
        }        
        
    }
}
