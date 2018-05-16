<?php

namespace App\Ticsol\Components\Models;

use App\Ticsol\Components\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = 'ts_resources';
    protected $primaryKey = 'resource_id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'resource_name',
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
     * Assosiated ACL rules to current resource.
     */
    public function ACLs()
    {
        return $this->hasMany(ACL::class);
    }

    /**
     * Availible permissions for current resource.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    #endregion
}
