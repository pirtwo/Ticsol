<?php

namespace App\Ticsol\Base\Criteria;

use App\Ticsol\Base\Criteria\Criteria;
use App\Ticsol\Base\Repository\Contract\IRepository;
use Illuminate\Http\Request;

class ClientCriteria extends Criteria
{
    protected $request;
    protected $clientId;

    public function __construct(Request $request)
    {
        $this->request = $request;   
        $this->clientId = $request->user()->client_id;     
    }

    public function apply($model, IRepository $repository)
    {
        $model->OfClient($this->clientId);
       
        return $model;
    }
}
