<?php

namespace App\Ticsol\Models;

use App\Ticsol\Models;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'ts_schedules';
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
        'schedule_offsite',
        'user_id',
        'job_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
