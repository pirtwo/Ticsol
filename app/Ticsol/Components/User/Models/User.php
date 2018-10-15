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
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'name',
        'email',
        'password',
        'isowner', 
        'meta'       
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function findForPassport($username) {
        return $this->where('name', $username)->first();
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
        return $this->hasMany(Role::class, 'creator_id');
    }

    /**
     * ACL rules created by current user.
     */
    public function createdACLs()
    {
        return $this->hasMany(ACL::class, 'creator_id');
    }

    /**
     * Schedule items created by current user.
     */
    public function createdSchedules()
    {
        return $this->hasMany(Schedule::class, 'creator_id');
    }

    /**
     * Contacts created by current user.
     */
    public function createdContacts()
    {
        return $this->hasMany(Contact::class, 'creator_id');
    }

    /**
     * Addresses created by current user.
     */
    public function createdAddresses()
    {
        return $this->hasMany(Address::class, 'creator_id');
    }

    /**
     * Banks created by current user.
     */
    public function createdBanks()
    {
        return $this->hasMany(Bank::class, 'creator_id');
    }

    /**
     * Jobs created by current user.
     */
    public function createdJobs()
    {
        return $this->hasMany(Job::class, 'creator_id');
    }

    /**
     * Forms created by current user.
     */
    public function createdForms()
    {
        return $this->hasMany(Form::class, 'creator_id');
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
        return $this->hasMany(Request::class, 'assigned_id');
    }

    /**
     * User scheduled items.
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
     * Invitations sended by current user.
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    #endregion
}
