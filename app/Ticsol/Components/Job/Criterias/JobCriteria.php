<?php

namespace App\Ticsol\Components\Job\Criterias;

use App\Ticsol\Base\Repository\Contract\IRepository;
use App\Ticsol\Base\Criteria\Criteria;
use Illuminate\Http\Request;

class JobCriteria extends Criteria
{
    protected $request;
    protected $showAll;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->showAll = $request->query("showall", false);
    }

    public function apply($model, IRepository $repository){ 
        
        // show active jobs
        if(!$this->showAll)
            $model->where("isactive", true);

        return $model;
    }
}
