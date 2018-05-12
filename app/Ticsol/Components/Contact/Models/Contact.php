<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use App\Ticsol\Components\Models;

class Contact extends Model
{    
    protected $table = 'ts_contacts';
    protected $primaryKey = 'contact_id';

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_contact', 'contact_id', 'job_id');
    }
}