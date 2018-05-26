<?php

namespace App\Ticsol\Components\Models;

use App\Ticsol\Components\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'ts_permissions';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
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
     * Assosiated ACL rules to current permission.
     */
    public function ACLs()
    {
        return $this->hasMany(ACL::class);
    }

    /**
     * Availible resources for current permission.
     */
    public function resources()
    {
        return $this->blongsToMany(Resource::class, 'accessible_permissions', 'permission_id', 'resource_id');
    }

    #endregion
}
