<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ticsol\Components\Models;

class Request extends Model
{
    protected $table = 'ts_requests';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_id',
        'form_id',
        'assigned_id',
        'schedule_id',

        // leave, reimbursement, timesheet
        'type',

        // submitted, approved, rejected, draft
        'status',
        'meta',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function scopeOfClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeRelatedRequests($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->orWhere('assigned_id', $userId);
    }

    #region Eloquent_Relationships

    /**
     * Assosiated client to current request.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * The owner of current request.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The assigned user to current request.
     */
    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_id');
    }

    /**
     * Assosiated job to current request.
     */
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    /**
     * Assosiated form to current request.
     */
    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    /**
     * Assosiated schedule item to current request.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    /**
     * Assosiated timesheets to current request.
     */
    public function timesheets()
    {
        return $this->hasMany(Schedule::class);
    }

    #endregion
}
