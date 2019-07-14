<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ticsol\Components\Models;

class Contact extends Model
{    
    protected $table = 'ts_contacts';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'group',
        'firstname',
        'lastname',
        'telephone',
        'mobilephone',
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
     * Assosiated client to current contact.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Owner of current contact.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Creator of current contact.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Assosiated addresses to current contact.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'contact_id');
    }

    /**
     * Assosiated banks to current contact.
     */
    public function banks()
    {
        return $this->hasMany(Bank::class, 'bank_id');
    }

    /**
     * Assosiated jobs to current contact.
     */
    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'ts_job_contact', 'contact_id', 'job_id')
            ->withPivot('type')->withTimestamps();
    }

    #endregion
}