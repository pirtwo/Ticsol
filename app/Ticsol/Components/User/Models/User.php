<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Ticsol\Components\Models;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'ts_users';
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

    public function findForPassport($username) {
        return $this->where('user_name', $username)->first();
    }


    // Eloquent Relationships

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function assignedRequests()
    {
        return $this->hasMany(Request::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
