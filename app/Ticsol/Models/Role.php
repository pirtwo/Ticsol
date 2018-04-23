<?php

namespace App\Ticsol\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{    
    protected $primaryKey = 'role_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}