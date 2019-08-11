<?php

namespace App\Ticsol\Components\Client\Repository;

use App\Ticsol\Base\Repository\Repository;

class ClientRepository extends Repository{
    
    public function model()
    {
        return 'App\Ticsol\Components\Models\Client';
    }    
}