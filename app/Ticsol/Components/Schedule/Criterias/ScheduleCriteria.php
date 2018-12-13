<?php

namespace App\Ticsol\Components\Schedule\Criterias;

use App\Ticsol\Base\Criteria\Criteria;
use App\Ticsol\Base\Repository\Contract\IRepository;
use Illuminate\Http\Request;

class ScheduleCriteria extends Criteria
{
    protected $request;
    protected $inRange;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->inRange =
        $request->query('inRange') ?? null;
    }

    public function apply($model, IRepository $repository)
    {
        if ($this->inRange != null) {
            $range = explode(',', $this->inRange);
            $model->where(function ($query) use ($range) {
                $query->where([
                    ['start', '>=', $range[0]],
                    ['start', '<=', $range[1]],
                ])->orWhere([
                    ['end', '>=', $range[0]],
                    ['end', '<=', $range[1]],
                ]);
            });
        }

        $model->scheduleItems();

        return $model;
    }
}
