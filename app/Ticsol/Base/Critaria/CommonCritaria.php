<?php

namespace App\Ticsol\Base\Criteria;

use App\Ticsol\Base\Criteria\Criteria;
use App\Ticsol\Base\Repository\Contract\IRepository;
use Illuminate\Http\Request;

class CommonCriteria extends Criteria
{
    protected $request;

    /**
     * Query string, CNT.
     * - Format: [colName],[pattern]
     * @var string
     */
    protected $contains;

    /**
     * Query string, NCNT.
     * - Format: [colName],[pattern]
     * @var string
     */
    protected $notContains;

    /**
     * Query string, EQ.
     * - Format: [colName],[value]
     * @var string
     */
    protected $equals;

    /**
     * Query string, NEQ.
     * - Format: [colName],[value]
     * @var string
     */
    protected $notEquals;

    /**
     * Query string, GT.
     * - Format: [colName],[value]
     * @var string
     */
    protected $greaterThan;

    /**
     * Query string, LT.
     * - Format: [colName],[value]
     * @var string
     */
    protected $lessThan;

    /**
     * Query string, GTE.
     * - Format: [colName],[value]
     * @var string
     */
    protected $greaterThanOrEqual;

    /**
     * Query string, LTE.
     * - Format: [colName],[value]
     * @var string
     */
    protected $lessThanOrEqual;

    /**
     * Query string, BTW.
     * - Format: [colName],[value1],[value2]
     * @var string
     */
    protected $between;

    /**
     * Query string, NBTW.
     * - Format: [colName],[value1],[value2]
     * @var string
     */
    protected $notBetween;

    /**
     * Query string, In.
     * - Format: [colName],[value1,value2,...]
     * @var string
     */
    protected $in;

    /**
     * Query string, orderby.
     * - Format: [colName],[ase or desc]
     * @var string
     */
    protected $orderby;

    /**
     * Query string, groupby.
     * - Format: [colName1,colName2,...]
     * @var string
     */
    protected $groupby;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->contains =
        $request->query('cnt') != null ? explode(',', $request->query('cnt')) : null;
        $this->notContains =
        $request->query('ncnt') != null ? explode(',', $request->query('ncnt')) : null;
        $this->equals =
        $request->query('eq') != null ? explode(',', $request->query('eq')) : null;
        $this->notEquals =
        $request->query('neq') != null ? explode(',', $request->query('neq')) : null;
        $this->lessThan =
        $request->query('lt') != null ? explode(',', $request->query('lt')) : null;
        $this->greaterThan =
        $request->query('gt') != null ? explode(',', $request->query('gt')) : null;
        $this->lessThanOrEqual =
        $request->query('lte') != null ? explode(',', $request->query('lte')) : null;
        $this->greaterThanOrEqual =
        $request->query('gte') != null ? explode(',', $request->query('gte')) : null;
        $this->between =
        $request->query('btw') != null ? explode(',', $request->query('btw')) : null;
        $this->notBetween =
        $request->query('nbtw') != null ? explode(',', $request->query('nbtw')) : null;
        $this->in =
        $request->query('in') != null ? explode(',', $request->query('in')) : null;
        $this->orderby =
        $request->query('orderby') != null ? explode(',', $request->query('orderby')) : null;
        $this->groupby =
        $request->query('groupby') != null ? explode(',', $request->query('groupby')) : null;
    }

    public function apply($model, IRepository $repository)
    {

        if ($this->contains != null) {
            $model->where($this->contains[0], 'like', '%' . $this->contains[1] . '%');
        }

        if ($this->notContains != null) {
            $model->where($this->notContains[0], 'not like', '%' . $this->notContains[1] . '%');
        }

        if ($this->equals != null) {
            $model->where($this->equals[0], '=', $this->equals[1]);
        }

        if ($this->notEquals != null) {
            $model->where($this->notEquals[0], '!=', $this->notEquals[1]);
        }

        if ($this->lessThan != null) {
            $model->where($this->lessThan[0], '<', $this->lessThan[1]);
        }

        if ($this->greaterThan != null) {
            $model->where($this->greaterThan[0], '>', $this->greaterThan[1]);
        }

        if ($this->lessThanOrEqual != null) {
            $model->where($this->lessThanOrEqual[0], '<=', $this->lessThanOrEqual[1]);
        }

        if ($this->greaterThanOrEqual != null) {
            $model->where($this->greaterThanOrEqual[0], '>=', $this->greaterThanOrEqual[1]);
        }

        if ($this->between != null) {
            $model->whereBetween($this->between[0], [$this->between[1], $this->between[2]]);
        }

        if ($this->notBetween != null) {
            $model->whereNotBetween($this->notBetween[0], [$this->notBetween[1], $this->notBetween[2]]);
        }

        if ($this->in != null) {                  
            $model->whereIn($this->in[0], \array_slice($this->in, 1));
        }

        if ($this->orderby != null) {
            $model->orderBy($this->orderby[0], $this->orderby[1]);
        }

        if ($this->groupby != null) {
            $model->groupBy($this->groupby);
        }

        return $model;
    }
}
