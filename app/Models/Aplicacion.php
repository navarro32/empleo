<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ApiHash;
use App\Traits\Help;

class Aplicacion extends Model
{
    use SoftDeletes,  Help;

    protected $table = 'aplicaciones';
    protected $fillable = [
        'empresa_id', 'user_id', 'ofertas', 'fechas', 'fecha_aplicacion'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'user_id'
    ];
    protected $dates = ['created_at', 'updated_at', 'deleted_at']; 
    
    public function getOfertasAttribute($value)
    {
      return json_decode($this->attributes['ofertas']);
    }

    public function setOfertasAttribute($value)
    {
      $this->attributes['ofertas'] = json_encode($value);
    }
    public function getFechasAttribute($value)
    {
      return json_decode($this->attributes['fechas']);
    }

    public function setFechasAttribute($value)
    {
      $this->attributes['fechas'] = json_encode($value);
    }
}