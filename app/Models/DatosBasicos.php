<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class DatosBasicos extends Model
{
    protected $table = 'datos_basicos';

    protected $fillable = [
        'user_id', 'telefono', 'celular', 'documento', 'fecha_nacimiento', 'genero', 'estado_civil', 'departamento', 'ciudad', 'direccion', 'ruta_avatar'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'user_id', 'id'
    ];

    public function getRutaAvatarAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL) === FALSE) {
            return asset(Storage::url($value));
        }else{
            return $value;
        }        
        
    }
}
