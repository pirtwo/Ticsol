<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use App\Ticsol\Components\Models;

class Request extends Model
{
    protected $table = 'ts_requests';
    protected $primaryKey = 'request_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_type',
        'request_status',
        'user_id',
        'manager_id',
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

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
