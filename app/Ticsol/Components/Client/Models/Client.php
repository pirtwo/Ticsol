<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ticsol\Components\Models;

class Client extends Model
{
    protected $table = 'ts_clients';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'licences',
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
     * Associated users to current client.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Associated roles to current client.
     */
    public function roles()
    {
        return $this->hasMany(Role::class);
    }    

    /**
     * Client created forms.
     */
    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    /**
     * Client ACL rules.
     */
    public function ACLs()
    {
        return $this->hasMany(ACL::class);
    }

    /**
     * Associated requests to current client.
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    /**
     * Associated jobs to current client.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Associated contacts to current client.
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Associated addresses to current client.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Associated banks to current client.
     */
    public function banks()
    {
        return $this->hasMany(Bank::class);
    }

    /**
     * Associated schedules to current client.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Associated activities to current client.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    #endregion
}
