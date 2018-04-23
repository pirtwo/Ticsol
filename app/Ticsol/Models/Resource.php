<?php

namespace App\Ticsol\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{    
    protected $primaryKey = 'resource_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'resource_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}