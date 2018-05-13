<?php

namespace App\Ticsol\Components\Models;

use App\Ticsol\Components\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ACL extends Model
{
    protected $table = 'ts_acls';
    protected $primaryKey = 'acl_id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
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

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
