<?php

namespace App\Ticsol\Components\Timesheet\Repository;

use App\Ticsol\Base\Repository\Repository;
use App\Ticsol\Components\Models\Timesheet;

class TimesheetRepository extends Repository{
    
    public function model()
    {
        return 'App\Ticsol\Components\Models\Timesheet';
    }    
}