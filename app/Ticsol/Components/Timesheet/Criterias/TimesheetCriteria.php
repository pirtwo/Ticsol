<?php

namespace App\Ticsol\Components\Timesheet\Criterias;

use App\Ticsol\Base\Criteria\Criteria;
use App\Ticsol\Base\Repository\Contract\IRepository;
use Illuminate\Http\Request;

class TimesheetCriteria extends Criteria
{
    protected $request;
    protected $userId;
    protected $inRange;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->userId =
        $request->user()->id;
        $this->inRange =
        $request->query('inRange') ?? null;
    }

    public function apply($model, IRepository $repository)
    {
        if ($this->inRange != null) {
            $range = explode(',', $this->inRange);
            $model->where(function ($query) use ($range) {
                $query->where([
                    ['week_start', '>=', $range[0]],
                    ['week_start', '<=', $range[1]],
                ])->orWhere([
                    ['week_end', '>=', $range[0]],
                    ['week_end', '<=', $range[1]],
                ]);
            });
        } 
        
        $model->OfUser($this->userId);

        return $model;
    }
}
