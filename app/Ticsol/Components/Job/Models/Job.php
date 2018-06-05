<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ticsol\Components\Models;

class Job extends Model
{    
    protected $table = 'ts_jobs';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [        
        'parent_id',
        'form_id',
        'title',
        'code',
        'isactive', 
        'meta',       
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


    #region Eloquent_Relationships

    /**
     * Assosiated client to current job.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Creator of current job.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Assosiated requests to current job.
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    /**
     * Assosiated schedules to current job.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Parent of current job.
     */
    public function parent()
    {
        return $this->belongsTo(Job::class, 'parent_id');
    }

    /**
     * Childs of current job.
     */
    public function childs()
    {
        return $this->hasMany(Job::class, 'parent_id');
    }

    /**
     * Assosiated form to current job.
     */
    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    #regionend
}