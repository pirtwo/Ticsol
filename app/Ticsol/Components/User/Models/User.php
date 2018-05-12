<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use App\Ticsol\Components\Models;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'ts_users';
    protected $primaryKey = 'user_id';
    protected $dates = ['deleted_at'];

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
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_password',
    ];

    public function getAuthPassword()
    {
        return $this->user_password;
    }

    public function findForPassport($username) {
        return $this->where('user_name', $username)->first();
    }


    // Eloquent Relationships

    /**
     * User associated client.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * User roles in system.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

    /**
     * User drafted requests.
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    /**
     * Assigned requests to the current user.
     */
    public function assignedRequests()
    {
        return $this->hasMany(Request::class);
    }

    /**
     * User schedule items.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * User contacts.
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * User password reset tokens.
     */
    public function resetToken()
    {
        return $this->hasMany(PasswordReset::class);
    }

    /**
     * Invitations sended by user.
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
