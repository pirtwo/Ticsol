<?php

namespace App\Ticsol\Components\Schedule\Repository;

use App\Ticsol\Base\Repository\Repository;
use App\Ticsol\Components\Models\Schedule;

class ScheduleRepository extends Repository{
    
    public function model()
    {
        return 'App\Ticsol\Components\Models\Schedule';
    }    
}