<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Database\Eloquent\Model;
use App\Ticsol\Components\Models;

class Address extends Model
{    
    protected $table = 'ts_Addresses';
    protected $primaryKey = 'address_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address_unit',
        'address_street',
        'address_suburb',
        'address_country',
        'address_postcode',
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