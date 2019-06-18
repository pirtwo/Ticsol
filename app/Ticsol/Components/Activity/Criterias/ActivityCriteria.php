<?php

namespace App\Ticsol\Components\Activity\Criterias;

use App\Ticsol\Base\Criteria\Criteria;
use App\Ticsol\Base\Repository\Contract\IRepository;
use Illuminate\Http\Request;

class ActivityCriteria extends Criteria
{
    protected $request;
    protected $job_id;
    protected $user_id;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->user_id = $request->user()->id;
    }

    public function apply($model, IRepository $repository)
    {

        $model->OfUser($this->user_id)->orderby('created_at', 'desc');

        return $model;
    }
}
