<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ticsol\Components\Models;

class Invitation extends Model
{    
    protected $table = 'ts_invitations';
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
        'email',
        'token',
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
     * Assosiated client to current invitation.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Creator of current invitation.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    #endregion
}