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
        'meta',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
