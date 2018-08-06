<?php

namespace App\Ticsol\Base\Repository;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;

use App\Ticsol\Base\Repository\Contract\ICriteria;
use App\Ticsol\Base\Repository\Contract\IRepository;

/**
 * Base class for eloquent model repository.
 */
abstract class Repository implements IRepository, ICriteria
{

    /**
     * Instance of Illuminate\Container\Container
     * @var App
     */
    private $app;

    /**
     * Eloquent model instance.
     * @var
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $criterias;

    /**
     * @var bool
     */
    protected $skipCriteria;

    public function __construct(App $app, Collection $collection)
    {
        $this->app = $app;
        $this->criterias = $collection;

        $this->resetScope();
        $this->makeModel();        
    }

    /**
     * Defines eloquent model class name.
     * @return mixed
     */
    abstract public function model();

    /**
     * Creates instance of Model.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model->newQuery();
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($with = [], $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->with($with)->get($columns);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->find($id, $columns);
    }

    /**
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($field, $value, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->where($field, '=', $value)->first($columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $with=[], $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->with($with)->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param string $id     
     * @return mixed
     */
    public function update(array $data, $id)
    {        
        return $this->model->find($id)->update($data);        
    }

    /**
     * @param $field
     * @param $value
     * @return mixed
     */
    public function delete($field, $value, $softDelete = true)
    {
        if($softDelete){
            return $this->model->where($field, '=', $value)->delete();
        }else{
            return $this->model->where($field, '=', $value)->forceDelete();
        }
    }

    /**
     * @param $field
     * @param $value
     * @return mixed
     */
    public function restore($field, $value)
    {
        return $this->model->where($field, '=', $value)->restore();
    }

    /**
     * @return $this
     */
    public function resetScope()
    {
        $this->skipCriteria(false);
        return $this;
    }

    /**
     * @param bool $status
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;
        return $this;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function pushCriteria(Criteria $criteria)
    {
        $this->criterias->push($criteria);
        return $this;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function getByCriteria(Criteria $criteria)
    {
        $this->model = $criteria->aplly($this->model, $this);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criterias;
    }

    /**
     * @return $this
     */
    public function applyCriteria()
    {
        if ($this->skipCriteria) {
            return $this;
        }

        foreach ($this->getCriteria() as $critaria) {
            if ($critaria instanceof Criteria) {
                $this->model = $critaria->apply($this->model, $this);
            }
        }

        return $this;
    }
}
