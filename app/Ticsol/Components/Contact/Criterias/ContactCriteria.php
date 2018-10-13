<?php

namespace App\Ticsol\Components\Contact\Criterias;

use App\Ticsol\Base\Criteria\Criteria;
use App\Ticsol\Base\Repository\Contract\IRepository;
use Illuminate\Http\Request;

class ContactCriteria extends Criteria
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        //
    }

    public function apply($model, IRepository $repository)
    {

        //

        return $model;
    }
}
