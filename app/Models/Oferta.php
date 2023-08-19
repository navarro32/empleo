<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ApiHash;
use Stevebauman\Purify\Facades\Purify;
use App\Traits\Help;
use Carbon\Carbon;

class Oferta extends Model
{
    const TIPO_CONTRATO  = [1=>'A término fijo', 2=>'Término indefinido', 
    3=>'Por prestación de servicios', 4=>'Por Obra o labor', 5=>'Freelance']; 
    
    use SoftDeletes, ApiHash, Help;
    protected $hidden = [
        'id', 'created_at', 'updated_at', 'user_id', 'deleted_at', 'area_id', 'ciudad_id'
    ];
    protected $appends  = ['key', 'area_key', 'ciudad_key', 'description', 'description_large', 'salario', 'publicado', 'slug', 'contrato_letra'];
    protected $dates = ['deleted_at']; 


    public function ciudad()
    {
        return $this->hasOne('App\Models\Ciudades', 'id', 'ciudad_id');
    }
    public function area()
    {
        return $this->hasOne('App\Models\Area', 'id', 'area_id');
    }
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
    public function scopeByDepart($query, $value)
    {
        return $query->with(['ciudad'=>function($query)use($value){
            $query->whereIn('departamento_id', $value);
        }]);
    }

    public function getAreaKeyAttribute($value)
    {
      return $this->hash_encode($this->area_id, \App\Models\Area::class);
    }

    public function setResearchTypeKeyAttribute($value)
    {
      return $this->attributes['area_id'] = $this->hash_decode($value, \App\Models\Area::class);
    }
    public function getCiudadKeyAttribute($value)
    {
      return $this->hash_encode($this->ciudad_id, \App\Models\Ciudades::class);
    }

    public function setCiudadKeyAttribute($value)
    {
      return $this->attributes['ciudad_id'] = $this->hash_decode($value, \App\Models\Ciudades::class);
    }

    public function getDescriptionAttribute(){
      $config = ['HTML.Allowed' => ''];
      return $this->limitar_cadena(Purify::clean($this->descripcion, $config), 100, '...');
    }

    public function getDescriptionLargeAttribute(){
      $config = ['HTML.Allowed' => ''];
      return $this->limitar_cadena(Purify::clean($this->descripcion, $config), 500, '...');
    }
    public function getSalarioAttribute(){
      return number_format($this->sueldo, 0, ',', '.');
    }
    public function getPublicadoAttribute(){
      return Carbon::parse($this->created_at)->locale('es_co')->toFormattedDateString();
    }
    public function getSlugAttribute(){
      return $this->slugify($this->titulo);
    }
    public function getContratoLetraAttribute(){      
      return Oferta::TIPO_CONTRATO[$this->contrato];
    }
}
