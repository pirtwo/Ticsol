<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ticsol\Components\Models;

class Bank extends Model
{    
    protected $table = 'ts_bank';
    protected $primaryKey = 'bank_id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contact_id',
        'bank_bsb',
        'bank_acc_name',
        'bank_acc_number',
        'bank_split',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}