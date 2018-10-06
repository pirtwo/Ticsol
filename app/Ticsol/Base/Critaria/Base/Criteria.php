<?php

namespace App\Ticsol\Base\Criteria;

use App\Ticsol\Base\Repository\Repository;
use App\Ticsol\Base\Repository\Contract\IRepository;

/**
 * Base class for criteria.
 */
abstract class Criteria{

    /**
     * @param $model
     * @param IRepository $repository
     * 
     * @return mixed
     */
    public abstract function apply($model, IRepository $repository); 
}