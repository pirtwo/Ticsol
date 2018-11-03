<?php

namespace App\Ticsol\Components\Role\Repository;

use App\Ticsol\Base\Repository\Repository;

class RoleRepository extends Repository{
    
    public function model()
    {
        return 'App\Ticsol\Components\Models\Role';
    }    
}