<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ticsol\Components\Models;

class Comment extends Model
{    
    protected $table = 'ts_comments';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $casts = [];
    protected $appends = ['reply_count'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'job_id',          
        'parent_id',
        'request_id',   
        'timesheet_id',   
        'creator_id', 
        'body',        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'client_id'               
    ];

    //====== Attributes =====
    public function getReplyCountAttribute()
    {
        return $this->attributes['replyCount'] = $this->childs()->count();
    }

    //====== Scopes =======
    public function scopeOfClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }


    #region Eloquent_Relationships

    /**
     * Assosiated client to current comment.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Creator of current comment.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Assosiated request to current comment.
     */
    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id');
    }

    /**
     * Assosiated timesheet to current comment.
     */
    public function timesheet()
    {
        return $this->belongsTo(Request::class, 'timesheet_id');
    }

    /**
     * Assosiated job to current comment.
     */
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
   
    /**
     * Childs of current comment.
     */
    public function childs()
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderby('created_at', 'desc');
    }   

    #regionend
}