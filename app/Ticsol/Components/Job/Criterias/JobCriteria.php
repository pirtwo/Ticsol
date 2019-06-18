<?php

namespace App\Ticsol\Components\Job\Criterias;

use App\Ticsol\Base\Repository\Contract\IRepository;
use App\Ticsol\Base\Criteria\Criteria;
use Illuminate\Http\Request;

class JobCriteria extends Criteria
{
    protected $request;
    protected $contains;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($model, IRepository $repository){  
        return $model;
    }
}
