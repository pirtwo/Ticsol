<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ticsol\Components\Models;

class Bank extends Model
{    
    protected $table = 'ts_banks';
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
        'contact_id',
        'bsb',
        'acc_name',
        'acc_number',
        'split',
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
     * Associated client to current bank.
     */
    public function client()
    {
        return $this->belongsTo(Contact::class, 'client_id');
    }

    /**
     * Creator of current bank.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Associated contact to current bank.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    #endregion
}