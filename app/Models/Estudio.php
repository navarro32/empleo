<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Estudio extends Model
{
    protected $table='estudio';
    protected $fillable = [
        'user_id', 'ciudad_id', 'estado', 'fecha_inicio', 'fecha_fin', 'titulo', 'institucion'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'user_id'
    ];

    protected $appends = ['tiempo'];

     /**
     * Obtiene el tiempo.
     *
     * @return bool
     */
    public function getTiempoAttribute()
    {
        $date1 = Carbon::createMidnightDate($this->attributes['fecha_inicio']);
        $date2 = Carbon::createMidnightDate($this->attributes['fecha_fin']);        
        return round($date1->floatDiffInMonths($date2),2);
    }
}
