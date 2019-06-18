<?php

namespace App\Ticsol\Components\Models;

use App\Ticsol\Components\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $table = 'ts_timesheets';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id',
        'week_start',
        'week_end',
        'total_hours',
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

    public function scopeOfUser($query, $userId)
    {
        return $query->where('creator_id', $userId)
            ->orWhere(function ($query) use ($userId) {
                $query->whereHas('request', function ($query) use ($userId) {
                    $query->where('assigned_id', $userId);
                });
            });
    }

    #region Eloquent_Relationships

    /**
     * Assosiated client to current timesheet.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Creator of current timesheet.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Assosiated job to current timesheet.
     */
    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id');
    }

    /**
     * Assosiated schedule items to current timesheet.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    #endregion
}
