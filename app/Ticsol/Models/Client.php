<?php

namespace App\Ticsol\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{    
    protected $primaryKey = 'client_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}