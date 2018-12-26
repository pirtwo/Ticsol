<?php

namespace App\Ticsol\Components\Comment\Criterias;

use App\Ticsol\Base\Criteria\Criteria;
use App\Ticsol\Base\Repository\Contract\IRepository;
use Illuminate\Http\Request;

class CommentCriteria extends Criteria
{
    protected $request;
    protected $entity;
    protected $id;

    public function __construct(Request $request, $entity, $id)
    {
        $this->request = $request;
        $this->entity = $entity;
        $this->id = $id;
    }

    public function apply($model, IRepository $repository)
    {

        if($this->entity == 'job'){
            $model->where('job_id', $this->id);
        }
        if($this->entity == 'request'){
            $model->where('request_id', $this->id);
        }
        $model->where('parent_id', null)->orderby('created_at', 'desc');

        return $model;
    }
}
