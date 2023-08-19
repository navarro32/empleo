<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ApiHash;
class Ciudades extends Model
{
    use  ApiHash;
    protected $table  = 'ciudad';

    protected $fillable = [
        'ciudad', 'departamento_id'
    ];    

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at', 'id', 'departamento_id'
    ];
    protected $appends  = ['key', 'departamento_key'];

    

    public function departamento()
    {
        return $this->belongsTo('App\Models\Departamento');
    }

    public function getDepartamentoKeyAttribute($value)
    {
      return $this->hash_encode($this->departamento_id, \App\Models\Departamento::class);
    }

    public function setDepartamentoKeyAttribute($value)
    {
      return $this->attributes['departamento_id'] = $this->hash_decode($value, \App\Models\Departamento::class);
    }
}
