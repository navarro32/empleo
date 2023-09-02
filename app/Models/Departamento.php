<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ApiHash;
class Departamento extends Model
{
    use  ApiHash;
    protected $table  = 'departamento';

    protected $fillable = [
        'departamento'
    ];    

    protected $hidden = [
        'created_at', 'updated_at'
    ];
    protected $appends  = ['key'];
    public function ciudades()
    {       
        return $this->hasMany('App\Models\Ciudades');
    }
}
