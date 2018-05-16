<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ticsol\Components\Models;

class Contact extends Model
{    
    protected $table = 'ts_contacts';
    protected $primaryKey = 'contact_id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'contact_group',
        'contact_firstname',
        'contact_lastname',
        'contact_telephone',
        'contact_mobilephone',
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
     * Owner of current contact.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Assosiated jobs to current contact.
     */
    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_contact', 'contact_id', 'job_id');
    }

    #endregion
}