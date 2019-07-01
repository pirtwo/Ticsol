<?php

namespace App\Ticsol\Components\Models;

use App\Ticsol\Components\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'ts_schedules';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'job_id',
        'parent_id',
        'timesheet_id',
        'status',
        'type',
        'event_type',
        'start',
        'end',
        'offsite',
        'break_length',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function scopeOfClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeScheduleItems($query)
    {
        return $query->where('type', 'schedule');
    }


    #region Eloquent_Relationships

    /**
     * Assosiated client to current schedule item.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Creator of current schedule item.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Assosiated user to current schedule item.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Assosiated request to current schedule item.
     */
    public function request()
    {
        return $this->hasOne(Request::class);
    }

    /**
     * Assosiated job to current schedule item.
     */
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }    

    /**
     * Assosiated timesheet to current schedule item.
     */
    public function timesheet()
    {
        return $this->belongsTo(Timesheet::class, 'timesheet_id');
    }    

    /**
     * Current schedule item activities.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Childs of current schedule.
     */
    public function childs()
    {
        return $this->hasMany(Schedule::class, 'parent_id');
    }

    #endregion
}
