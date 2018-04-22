<?php

namespace App\Models;

class Schedule 
{    
    protected $primaryKey = 'schedule_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'schedule_status',
        'schedule_type',
        'schedule_event',
        'schedule_start',
        'schedule_end',
        'user_id',
        'job_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}