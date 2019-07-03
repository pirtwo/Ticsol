<?php

namespace App\Ticsol\Components\Team\Repository;

use App\Ticsol\Base\Repository\Repository;
use App\Ticsol\Components\Models\Team;

class TeamRepository extends Repository
{
    public function model()
    {
        return 'App\Ticsol\Components\Models\Team';
    }
}
