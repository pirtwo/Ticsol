<?php

namespace App\Ticsol\Components\Form\Repository;

use App\Ticsol\Base\Repository\Repository;
use App\Ticsol\Components\Models\Form;

class FormRepository extends Repository{
    
    public function model()
    {
        return 'App\Ticsol\Components\Models\Form';
    }
}