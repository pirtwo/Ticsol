<?php

namespace App\Ticsol\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{    
    protected $primaryKey = 'request_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_type',
        'request_status',
        'user_id',
        'job_id',
        'meta'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}