<?php

namespace App\Ticsol\Base\Repository\Contract;

use App\Ticsol\Base\Repository\Criteria;

/**
 * Base contrac for criteria.
 */
interface ICriteria
{

    /**
     * @param bool $status
     * @return $this
     */
    public function skipCriteria($status = false);

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function pushCriteria(Criteria $criteria);

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function getByCriteria(Criteria $criteria);

    /**
     * @return mixed
     */
    public function getCriteria();

    /**
     * @return $this
     */
    public function applyCriteria();
}
