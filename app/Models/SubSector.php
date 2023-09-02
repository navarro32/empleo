<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubSector extends Model
{
    
    protected $table  = 'subsectores';

    protected $fillable = [
        'subsector', 'sector_id'
    ];    

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function sector()
    {
        return $this->belongsTo('App\Models\Sector');
    }
}
