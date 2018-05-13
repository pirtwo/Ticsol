<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use App\Ticsol\Components\Models;

class PasswordReset extends Model
{    
    protected $table = 'ts_password_reset'; 
    protected $primaryKey = 'passreset_id';   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'passreset_email',
        'passreset_token',        
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
        return $this->belongsTo(User::class);
    }
}