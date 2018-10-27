<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ticsol\Components\Models;

class Role extends Model
{
    protected $table = 'ts_roles';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'creator_id',        
        'name',        
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
     * Assosiated client to current role.
     */
    public function client()
    {
        $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Assosiated users to current role.
     */
    public function users()
    {
        $this->belongsToMany(User::class);
    }

    /**
     * The creator of current role.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Effective ACL rules for current role.
     */
    public function Permissions()
    {
        return $this->hasMany(ACL::class);
    }

    #endregion
}
