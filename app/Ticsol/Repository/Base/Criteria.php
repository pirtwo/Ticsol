<?php

namespace App\Ticsol\Repository\Base;

use App\Ticsol\Repository\Contract\IRepository;
use App\Ticsol\Repository\base\Repository;

abstract class Criteria{

    /**
     * @param $model
     * @param IRepository $repository
     * 
     * @return mixed
     */
    public abstract function aplly($model, IRepository $repository); 
}