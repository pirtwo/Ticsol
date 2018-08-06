<?php

namespace App\Ticsol\Components\Activity\Repository;

use App\Ticsol\Base\Repository\Repository;
use App\Ticsol\Components\Models\Activity;

class ActivityRepository extends Repository{
    
    public function model()
    {
        return 'App\Ticsol\Components\Models\Activity';
    }
}