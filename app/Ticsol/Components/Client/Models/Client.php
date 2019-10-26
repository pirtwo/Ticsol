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
    protected $casts = [
        'qbs' => 'array',
        'settings' => 'array',
        'billing_settings' => 'array',
        'billing_defaults' => 'array',
        'reimbursement_settings' => 'array',
        'meta' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'meta',
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

    /**
     * return client QBs access token.
     */
    public function getQBsToken()
    {
        $settings = $this->qbs ? $this->qbs : [];
        return \array_key_exists("token", $settings) ? $settings["token"] : null;
    }

    /**
     * store client QBs access token.
     */
    public function saveQBsToken($token)
    {
        $settings = $this->qbs ? $this->qbs : [];
        $settings["token"] = $token;
        $this->qbs = $settings;
        $this->save();
    }

    /**
     * check client for QBs access token.
     */
    public function hasQBsToken()
    {
        $settings = $this->qbs;

        if (!$settings) {return false;}

        return \array_key_exists("token", $settings) && $settings["token"] != null;
    }

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

    /**
     * Associated webhooks to current client.
     */
    public function webhooks()
    {
        return $this->hasMany(Webhook::class);
    }

    #endregion
}
