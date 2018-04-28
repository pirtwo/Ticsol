<?php

namespace App\Ticsol\Models;

use Illuminate\Database\Eloquent\Model;
use App\Ticsol\Models;

class Job extends Model
{    
    protected $table = 'ts_jobs';
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

    public function Requests()
    {
        return $this->hasMany(Request::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}