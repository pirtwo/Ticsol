<?php

namespace App\Ticsol\Components\Request\Criterias;

use App\Ticsol\Base\Repository\Contract\IRepository;
use App\Ticsol\Base\Criteria\Criteria;
use Illuminate\Http\Request;

class RequestCriteria extends Criteria
{
    protected $userId;
    protected $request;    

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->userId = $request->user()->id; 
    }

    public function apply($model, IRepository $repository)
    {
        $model->relatedRequests($this->userId);
        $model->orderby('created_at', 'desc');
        return $model;
    }
}
