<?php

namespace App\Ticsol\Components\Models;

use App\Ticsol\Components\Models;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $table = 'ts_forms';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $appends = [
        'depth',
        'hierarchy',
    ];
    protected $casts = [
        'schema' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'name',
        'schema',
        'billable',
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

    //--------- Attributes --------

    public function getHierarchyAttribute()
    {
        $item = $this;
        $hierarchy = collect([]);

        for ($i = 0; $i < 3; $i++) {
            $hierarchy->prepend($item->name);
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


    #region Eloquent_Relationships

    /**
     * Owner of the form.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Creator of the form.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Parent of current job.
     */
    public function parent()
    {
        return $this->belongsTo(Form::class, 'parent_id');
    }

    /**
     * Childs of current Form.
     */
    public function childs()
    {
        return $this->hasMany(Form::class, 'parent_id');
    }

    /**
     * Associated jobs to current form.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Associated requests to current form.
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    #endregion
}
