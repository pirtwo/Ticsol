<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ticsol\Components\Models;

class Client extends Model
{
    protected $table = 'ts_clients';
    protected $primaryKey = 'client_id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_name',
        'client_licences',
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

    #endregion
}
