<?php

namespace App\Ticsol\Base\Criteria;

use App\Ticsol\Base\Criteria\Criteria;
use App\Ticsol\Base\Repository\Contract\IRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommonCriteria extends Criteria
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($model, IRepository $repository)
    {        
        $query = explode('&', $this->request->server->get('QUERY_STRING'));        
        $query = preg_replace("/\%20/", " ", $query);
        $subQuery = [];

        if ($query[0] == "") {            
            return $model;
        }        

        foreach ($query as $key => $value) {
            $subQuery = explode("=", $value);            
            $operator = $subQuery[0];
            $subset = explode(",", $subQuery[1]);
            if ($operator == "cnt") {
                //$model->where($subset[0], 'like', '%' . $subset[1] . '%');
                $this->operation($model, "where", $subset[0], "like", '%' . $subset[1] . '%');
            } elseif ($operator == "ncnt") {
                //$model->where($subset[0], 'not like', '%' . $subset[1] . '%');
                $this->operation($model, "where", $subset[0], "not like", '%' . $subset[1] . '%');
            } elseif ($operator == "eq") {
                $this->operation($model, "where", $subset[0], "=", $subset[1]);
            } elseif ($operator == "neq") {
                //$model->where($subset[0], '!=', $subset[1]);
                $this->operation($model, "where", $subset[0], "!=", $subset[1]);
            } elseif ($operator == "lt") {
                //$model->where($subset[0], '<', $subset[1]);
                $this->operation($model, "where", $subset[0], "<", $subset[1]);
            } elseif ($operator == "gt") {
                //$model->where($subset[0], '>', $subset[1]);
                $this->operation($model, "where", $subset[0], ">", $subset[1]);
            } elseif ($operator == "lte") {
                //$model->where($subset[0], '<=', $subset[1]);
                $this->operation($model, "where", $subset[0], "<=", $subset[1]);
            } elseif ($operator == "gte") {
                //$model->where($subset[0], '>=', $subset[1]);
                $this->operation($model, "where", $subset[0], ">=", $subset[1]);
            } elseif ($operator == "btw") {
                //$model->whereBetween($subset[0], [$subset[1], $subset[2]]);
                $this->operation($model, "whereBetween", $subset[0], "", [$subset[1], $subset[2]]);
            } elseif ($operator == "nbtw") {
                //$model->whereNotBetween($subset[0], [$subset[1], $subset[2]]);
                $this->operation($model, "whereNotBetween", $subset[0], "", [$subset[1], $subset[2]]);
            } elseif ($operator == "in") {
                //$model->whereIn($subset[0], \array_slice($subset, 1));
                $this->operation($model, "whereIn", $subset[0], "", array_slice($subset, 1));
            } elseif ($operator == "orderby") {
                //$model->orderBy($subset[0], $subset[1]);
                $this->operation($model, "orderBy", $subset[0], "", $subset[1]);
            } elseif ($operator == "groupby") {
                //$model->groupBy($subset[0]);
                $this->operation($model, "groupBy", $subset[0], "", []);
            }
        }

        return $model;
    }

    protected function operation($model, $func, $column, $operation, $values)
    {
        if (strpos($column, ".")) {
            $decodedColumn = explode(".", $column);
            $fieldName = $decodedColumn[sizeof($decodedColumn) - 1];
            $entityRelation = implode(".", array_slice($decodedColumn, 1, sizeof($decodedColumn) - 2));

            $model->whereHas($entityRelation, function ($q) use ($func, $fieldName, $operation, $values) {
                if ($func == "where") {
                    $q->where($fieldName, $operation, $values);
                } else if ($func == "whereBetween") {
                    $q->whereBetween($fieldName, $values);
                } else if ($func == "whereNotBetween") {
                    $q->whereNotBetween($fieldName, $values);
                } else if ($func == "whereIn") {
                    $q->whereIn($fieldName, $values);
                } else if ($func == "orderBy") {
                    $q->orderBy($fieldName, $values);
                } elseif ($func == "groupBy") {
                    $q->groupBy($fieldName);
                }
            });
        } else {
            if ($func == "where") {
                $model->where($column, $operation, $values);
            } else if ($func == "whereBetween") {
                $model->whereBetween($column, $values);
            } else if ($func == "whereNotBetween") {
                $model->whereNotBetween($column, $values);
            } else if ($func == "whereIn") {
                $model->whereIn($column, $values);
            } else if ($func == "orderBy") {
                $model->orderBy($column, $values);
            } elseif ($func == "groupBy") {
                $model->groupBy($column);
            }
        }
    }

}
