<?php

namespace App\Ticsol\Components\Models;

use App\Ticsol\Components\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\WebhookServer\WebhookCall;

class Webhook extends Model
{
    protected $table = 'ts_webhooks';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'event'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    public function fire($data)
    {
        WebhookCall::create()
            ->url($this->url)
            ->payload($data)
            ->useSecret('secret')
            ->dispatch();
    }
      

    #region Eloquent_Relationships

    /**
     * Assosiated client to current schedule item.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Creator of current schedule item.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    #endregion
}
