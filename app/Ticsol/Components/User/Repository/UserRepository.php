<?php

namespace App\Ticsol\Components\User\Repository;

use App\Ticsol\Base\Repository\Repository;
use App\Ticsol\Components\Models\User;

class UserRepository extends Repository{
    
    public function model()
    {
        return 'App\Ticsol\Components\Models\User';
    }    
}