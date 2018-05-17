<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ticsol\Components\Models;

class Request extends Model
{
    protected $table = 'ts_requests';
    protected $primaryKey = 'request_id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_type',
        'request_status',
        'user_id',
        'assigned_id',
        'job_id',
        'form_id',
        'request_meta',
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
     * The owner of current request.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The assigned user to current request.
     */
    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_id');
    }

    /**
     * Assosiated job to current request.
     */
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    #endregion
}
