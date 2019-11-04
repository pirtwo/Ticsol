<?php

namespace App\Ticsol\Components\Models;

use App\Ticsol\Components\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'ts_activities';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [        
        'schedule_id',
        'job_id',
        'from',
        'till',
        'desc',        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'client_id',
        'creator_id',  
    ];    

    public function scopeOfClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }
    
    public function scopeOfCreator($query, $creatorId)
    {
        return $query->where('creator_id', $creatorId);
    }

    #region Eloquent_Relationships

    /**
     * Associated client to current activity.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Creator of current activity.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Associated schedule to current activity.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    /**
     * Associated job to current activity.
     */
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    #endregion
}
