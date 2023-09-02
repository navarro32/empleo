<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'perfil_laboral';

    protected $fillable = [
        'user_id', 'profesion', 'descripcion', 'experiencia', 'aspiracion', 'movilidad'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'user_id', 'id'
    ];
}
