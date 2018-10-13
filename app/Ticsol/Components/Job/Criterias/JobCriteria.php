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
        $this->contains = 
            $request->query('contains');
    }

    public function apply($model, IRepository $repository){
        
        if($this->contains != null){
            $model->where('title', 'like', $this->contains);
        }

        return $model;
    }
}
