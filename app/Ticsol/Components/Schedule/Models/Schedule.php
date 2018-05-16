<?php

namespace App\Ticsol\Components\Models;

use App\Ticsol\Components\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'ts_schedules';
    protected $primaryKey = 'schedule_id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'schedule_status',
        'schedule_type',
        'schedule_event_type',
        'schedule_start',
        'schedule_end',
        'schedule_offsite',
        'schedule_breake_length',
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

    #region Eloquent_Relationships

    /**
     * Assosiated user to current schedule item.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Assosiated job to current schedule item.
     */
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    /**
     * Current schedule item activities.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    #endregion
}
