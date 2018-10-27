<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ticsol\Components\Models;

class Address extends Model
{    
    protected $table = 'ts_Addresses';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [        
        'contact_id',
        'client_id',
        'creator_id',
        'unit',
        'number',
        'street',
        'suburb',
        'country',
        'postcode',
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
     * Associated client to current address.
     */
    public function client()
    {
        return $this->belongsTo(Contact::class, 'client_id');
    }

    /**
     * Creator of current address.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Associated contact to current address.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    #endregion
}