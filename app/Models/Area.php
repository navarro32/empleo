<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table  = 'area';

    protected $fillable = [
        'area'
    ];    

    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
