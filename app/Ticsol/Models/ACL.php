<?php

namespace App\Ticsol\Models;

use Illuminate\Database\Eloquent\Model;
use App\Ticsol\Models;

class ACL extends Model
{    
    protected $primaryKey = 'acl_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'resource_id',
        'permission_id',
        'role_id',
        'client_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}