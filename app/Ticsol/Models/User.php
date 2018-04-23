<?php

namespace App\Ticsol\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name',
        'user_email',
        'user_password',
        'user_isowner',
        'client_id',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_password', 'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->user_password;
    }

    public function role()
    {
        $this->belongsTo('App\Ticsol\Models\Role', 'role_id');
    }
}
