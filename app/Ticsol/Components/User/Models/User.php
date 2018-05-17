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


    #region Eloquent Relationships

    /**
     * Assosiated client to current user.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Roles of current user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

    /**
     * Roles created by current user.
     */
    public function createdRoles()
    {
        return $this->hasMany(Role::class);
    }

    /**
     * Schedule items created by current user.
     */
    public function createdSchedules()
    {
        return $this->hasMany(Schedule::class, 'creator_id', 'user_id');
    }

    /**
     * Forms created by current user.
     */
    public function createdForms()
    {
        return $this->hasMany(Form::class);
    }

    /**
     * Requests of current user.
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
     * User scheduled items.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'user_id', 'user_id');
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
     * Invitations sended by current user.
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    #endregion
}
