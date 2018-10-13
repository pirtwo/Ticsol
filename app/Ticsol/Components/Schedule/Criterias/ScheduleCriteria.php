<?php

namespace App\Ticsol\Components\Schedule\Criterias;

use App\Ticsol\Base\Repository\Contract\IRepository;
use App\Ticsol\Base\Criteria\Criteria;
use Illuminate\Http\Request;

class ScheduleCriteria extends Criteria
{
    protected $request;
    protected $startTime;
    protected $endTime;
    protected $startBetween;
    protected $endtBetween;
    protected $inRange;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->startTime =
            $request->query('start') ?? null;
        $this->endTime =
            $request->query('end') ?? null;
        $this->startBetween =
            $request->query('startBetween') ?? null;
        $this->endBetween =
            $request->query('endBetween') ?? null;
        $this->inRange= 
            $request->query('inRange') ?? null;
    }

    public function apply($model, IRepository $repository)
    {
        if ($this->startTime != null) {
            $model->where('start', '>=', $this->startTime);
        }

        if ($this->endTime != null) {
            $model->where('end', '<=', $this->endTime);
        }

        if ($this->startBetween != null) {
            $boundry = explode(',', $this->startBetween);
            $model->whereBetween('start', $boundry);
        }

        if ($this->endBetween != null) {
            $boundry = explode(',', $this->endBetween);
            $model->whereBetween('end', $boundry);
        }

        if ($this->inRange != null) {
            $range = explode(',', $this->inRange);
            $model->where([
                ['start', '>=', $range[0]],
                ['start', '<=', $range[1]]
            ])->orWhere([
                ['end', '>=', $range[0]],
                ['end', '<=', $range[1]]
            ]);
        }

        return $model;

    }
}
