<?php

namespace App\Ticsol\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{    
    protected $primaryKey = 'job_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_title',
        'job_code',
        'job_isactive',
        'parent_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}