<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $table  = 'sectores';

    protected $fillable = [
        'sector'
    ];    

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function subsectores()
    {       
        return $this->hasMany('App\Models\SubSector');
    }
}
