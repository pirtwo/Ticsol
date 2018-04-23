<?php

namespace App\Ticsol\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{    
    protected $primaryKey = 'permission_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}