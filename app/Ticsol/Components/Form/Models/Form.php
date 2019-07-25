<?php

namespace App\Ticsol\Components\Models;

use App\Ticsol\Components\Models;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $table = 'ts_forms';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
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
