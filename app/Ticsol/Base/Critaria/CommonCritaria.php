<?php

namespace App\Ticsol\Base\Criteria;

use App\Ticsol\Base\Criteria\Criteria;
use App\Ticsol\Base\Exceptions\InvalidCriteriaException;
use App\Ticsol\Base\Repository\Contract\IRepository;
use Illuminate\Http\Request;

class CommonCriteria extends Criteria
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($model, IRepository $repository)
    {
        try {
            $query = \explode('&', $this->request->server->get('QUERY_STRING'));
            $query = \preg_replace("/\%20/", " ", $query);
            $query = \preg_replace("/-\%3E/", "->", $query);
            $subQuery = [];

            if ($query[0] == "") {
                return $model;
            }

            foreach ($query as $key => $value) {
                $subQuery = \explode("=", $value);
                $operator = $subQuery[0];
                $subset = \explode(",", $subQuery[1]);

                if ($operator == "cnt") {
                    $this->operation($model, "where", $subset[0], "like", '%' . $subset[1] . '%');
                } elseif ($operator == "ncnt") {
                    $this->operation($model, "where", $subset[0], "not like", '%' . $subset[1] . '%');
                } elseif ($operator == "eq") {
                    $this->operation($model, "where", $subset[0], "=", $subset[1]);
                } elseif ($operator == "neq") {
                    $this->operation($model, "where", $subset[0], "!=", $subset[1]);
                } elseif ($operator == "lt") {
                    $this->operation($model, "where", $subset[0], "<", $subset[1]);
                } elseif ($operator == "gt") {
                    $this->operation($model, "where", $subset[0], ">", $subset[1]);
                } elseif ($operator == "lte") {
                    $this->operation($model, "where", $subset[0], "<=", $subset[1]);
                } elseif ($operator == "gte") {
                    $this->operation($model, "where", $subset[0], ">=", $subset[1]);
                } elseif ($operator == "btw") {
                    $this->operation($model, "whereBetween", $subset[0], "", [$subset[1], $subset[2]]);
                } elseif ($operator == "nbtw") {
                    $this->operation($model, "whereNotBetween", $subset[0], "", [$subset[1], $subset[2]]);
                } elseif ($operator == "in") {
                    $this->operation($model, "whereIn", $subset[0], "", array_slice($subset, 1));
                } elseif ($operator == "ob") {
                    $this->operation($model, "orderby", $subset[0], "", $subset[1]);
                } elseif ($operator == "gb") {
                    $this->operation($model, "groupby", $subset[0], "", []);
                }
            }

            return $model;

        } catch (\Exception $e) {
            throw new InvalidCriteriaException();
        }
    }

    protected function operation($model, $func, $column, $operation, $values)
    {
        // Try to convert string to number
        if (\is_array($values)) {
            $collec = collect($values);
            $collec = $collec->map(function ($item, $key) {
                if (\is_numeric($item)) {
                    return (float) $item;
                } else {
                    return $item;
                }
            });
            $values = $collec->all();
        }

        if (\strpos($column, ".")) {
            $decodedColumn = \explode(".", $column);
            $fieldName = $decodedColumn[\sizeof($decodedColumn) - 1];
            $entityRelation = \implode(".", \array_slice($decodedColumn, 1, \sizeof($decodedColumn) - 2));

            $model->whereHas($entityRelation, function ($q) use ($func, $fieldName, $operation, $values) {
                if ($func == "where") {
                    $q->where($fieldName, $operation, $values);
                } else if ($func == "whereBetween") {
                    $q->whereBetween($fieldName, $values);
                } else if ($func == "whereNotBetween") {
                    $q->whereNotBetween($fieldName, $values);
                } else if ($func == "whereIn") {
                    $q->whereIn($fieldName, $values);
                } else if ($func == "orderby") {
                    $q->orderby($fieldName, $values);
                } elseif ($func == "groupby") {
                    $q->groupby($fieldName);
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
            } else if ($func == "orderby") {
                $model->orderby($column, $values);
            } elseif ($func == "groupby") {
                $model->groupby($column);
            }
        }
    }

}
