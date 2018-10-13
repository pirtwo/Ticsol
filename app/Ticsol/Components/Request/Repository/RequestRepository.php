<?php

namespace App\Ticsol\Components\Request\Repository;

use App\Ticsol\Base\Repository\Repository;
use App\Ticsol\Components\Models\Request;

class RequestRepository extends Repository{
    
    public function model()
    {
        return 'App\Ticsol\Components\Models\Request';
    }
}