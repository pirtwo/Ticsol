<?php

namespace App\Ticsol\Models;

use App\Ticsol\Models;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'ts_permissions';
    protected $primaryKey = 'permission_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission_name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function ACLs()
    {
        return $this->hasMany(ACL::class);
    }

    public function resources()
    {
        return $this->blongsToMany(Resource::class, 'accessible_permissions', 'permission_id', 'resource_id');
    }
}
