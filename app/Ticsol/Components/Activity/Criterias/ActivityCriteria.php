<?php

namespace App\Ticsol\Components\Activity\Criterias;

use App\Ticsol\Base\Criteria\Criteria;
use App\Ticsol\Base\Repository\Contract\IRepository;
use App\Ticsol\Components\Models\Activity;
use Illuminate\Http\Request;

class ActivityCriteria extends Criteria
{
    protected $request;
    protected $user;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->user = $request->user();
    }

    public function apply($model, IRepository $repository)
    {
        if (!$this->user->can('listAll', Activity::class)) {
            $model->OfCreator($this->user->id);
        }

        return $model->orderby('created_at', 'desc');
    }
}
