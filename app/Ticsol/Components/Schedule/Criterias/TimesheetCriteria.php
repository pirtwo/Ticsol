<?php

namespace App\Ticsol\Components\Schedule\Criterias;

use App\Ticsol\Base\Repository\Contract\IRepository;
use App\Ticsol\Base\Criteria\Criteria;
use Illuminate\Http\Request;

class TimesheetCriteria extends Criteria
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
        $this->inRange= 
            $request->query('inRange') ?? null;
    }

    public function apply($model, IRepository $repository)
    {
        
        $model->where('type', 'timesheet');
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
