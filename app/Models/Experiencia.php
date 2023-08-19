<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Experiencia extends Model
{
    protected $table = 'experiencias';

    protected $fillable = [
        'user_id', 'empresa', 'sector', 'subsector', 'fecha_inicio', 'fecha_fin', 
        'trabajo_aqui', 'cargo', 'responsabilidades', 'telefono', 'ciudad'
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

    // public function getFechaInicioAttribute(){
    //     return Carbon::parse($this->attributes['fecha_inicio'])->format('d/m/Y');
    // }

    // public function setFechaInicioAttribute($value){
    //     $this->attributes['fecha_inicio']=Carbon::parse($value)->format('Y-m-d');
    // }
    
    // public function getFechaFinAttribute(){
    //     return (!is_null($this->attributes['fecha_fin']))?Carbon::parse($this->attributes['fecha_fin'])->format('d/m/Y'):'';
    // }
    // public function setFechaFinAttribute($value){
    //     $this->attributes['fecha_fin']=Carbon::parse($value)->format('Y-m-d');
    // }

    /**
     * Relación con sectores
     *
     * @return bool
     */

    public function sectorselected(){
        return $this->hasOne('App\Models\Sector', 'id', 'sector');
    }
    /**
     * Relación con subsectores
     *
     * @return bool
     */

    public function subsectorselected(){
        return $this->hasOne('App\Models\SubSector', 'id', 'subsector');
    }
}
