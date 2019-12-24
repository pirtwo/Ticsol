<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ticsol\Components\Models;

class Job extends Model
{    
    protected $table = 'ts_jobs';
    protected $primaryKey = 'id';
    protected $appends = [
        'depth',
        'hierarchy',
        'billable',
        'commentsCount', 
        'requestsCount', 
        'reportsCount', 
        'contactsCount',
        'childsCount'
    ];
    protected $casts = [
        'qbs' => 'array',
        'billing' => 'array',
        'profile' => 'array',
        'meta' => 'array',
    ];

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
        'color',
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

    public function scopeOfClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    
    //------ Attributes -------

    public function getHierarchyAttribute()
    {
        $item = $this;
        $hierarchy = collect([]);

        for ($i = 0; $i < 3; $i++) {
            $hierarchy->prepend($item->title);
            $item = $item->parent()->first();
            if ($item === null) {
                break;
            }
        }

        return $hierarchy->implode("/");
    }

    public function getDepthAttribute()
    {
        $item = $this;
        $depth = 0;

        for ($i = 0; $i < 3; $i++) {
            $item = $item->parent()->first();
            if ($item === null) {
                break;
            } else {
                $depth++;
            }
        }

        return $depth;
    }
    
    public function getBillableAttribute()
    {
        $profile = $this->profile()->first();
        if($profile)
            return $profile->billable === 1;
        else return false;
    }


    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    public function getReportsCountAttribute()
    {
        return $this->activities()->count();
    }

    public function getRequestsCountAttribute()
    {
        return $this->requests()->count();
    }

    public function getChildsCountAttribute()
    {
        return $this->childs()->count();
    }

    public function getContactsCountAttribute()
    {
        return $this->contacts()->count();
    }


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
     * Assosiated profile to current job.
     */
    public function profile()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    /**
     * Assosiated contacts to current job.
     */
    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'ts_job_contact', 'job_id', 'contact_id')
            ->withPivot('type')->withTimestamps();
    }

    /**
     * Activities of current job.
     */
    public function Activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Activities of current job.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    #regionend
}