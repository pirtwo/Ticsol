<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ticsol\Components\Models;

class Job extends Model
{    
    protected $table = 'ts_jobs';
    protected $primaryKey = 'job_id';
    protected $dates = ['deleted_at'];

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

    public function parent()
    {
        return $this->belongsTo(Job::class, 'parent_id');
    }

    public function childs()
    {
        return $this->hasMany(Job::class, 'parent_id');
    }
}