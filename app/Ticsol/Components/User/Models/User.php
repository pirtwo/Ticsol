<?php

namespace App\Ticsol\Components\Models;

use App\Ticsol\Components\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'ts_users';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $appends = ['fullname', 'avatar', 'permissions'];
    protected $casts = [
        'qbs' => 'array',
        'settings' => 'array',
        'meta' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'firstname',
        'lastname',
        'settings',
        'meta',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * find database column for password.
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * find user unique field for passport.
     */
    public function findForPassport($username)
    {
        return $this->where('email', $username)->first();
    }

    /**
     * filters users based on client ID.
     */
    public function scopeOfClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * User premissions attribute.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissionsAttribute()
    {
        $roles = $this->roles()->get();
        $permissions = collect([]);
        foreach ($roles as $role) {
            $rolePermissions = $role->permissions()->get();
            foreach ($rolePermissions as $permission) {
                if (!$permissions->contains($permission->name)) {
                    $permissions->push($permission->name);
                }
            }
        }
        return $permissions;
    }

    /**
     * User fullname attribute.
     *
     * @return String;
     */
    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * User avatar attribute.
     *
     * @return String;
     */
    public function getAvatarAttribute()
    {
        $meta = $this->meta ? $this->meta : [];
        $avatar = \array_key_exists("avatar", $meta) ? $meta["avatar"] : null;

        if ($avatar) {
            return $avatar;
        } else {
            return '/img/avatar/default.png';
        }
    }

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'App.Users.' . $this->id;
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
        return $this->belongsToMany(Role::class, 'ts_user_role', 'user_id', 'role_id');
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
     * User scheduled items.
     */
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class, 'creator_id');
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

    /**
     * Assosiated teams to this user.
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'ts_user_team');
    }

    public function webhooks()
    {
        return $this->hasMany(Webhook::class);
    }

    #endregion
}
