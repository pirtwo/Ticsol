<?php

namespace App\Ticsol\Components\Models;

use App\Ticsol\Components\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ACL extends Model
{
    protected $table = 'ts_acls';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'creator_id',
        'role_id',
        'client_id',
        'resource_id',
        'permission_id',
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
     * Owner of current ACL rule.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Creator of current ACL rule.
     */
    public function creator()
    {
        return $this->belongsTo(Client::class, 'creator_id');
    }

    /**
     * Effectied role by current rule.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Effectied permission by current rule.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    /**
     * Effectied resource by current rule.
     */
    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }

    #endregion
}
