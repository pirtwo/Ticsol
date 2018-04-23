<?php

namespace App\Ticsol\Repository\Base;

use App\Ticsol\Repository\Contract\IRepository;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;

/**
 * Concret class for eloquent model repository.
 */
abstract class Repository implements IRepository
{

    /**
     * Instance of Illuminate\Container\Container
     */
    private $app;

    /**
     * Eloquent model instance.
     */
    protected $model;


    public function __construct(App $app)
    {
        $this->app = $app;
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
        $model = $app->make($this->model());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }
        
        return $this->model = $model->newQuery();
    }


    /**
     * @param array $columns
     * 
     * @return mixed
     */
    public function all($columns = array('*'))
    {        
        return $this->model->get($columns);
    }


    /**
     * @param $id
     * @param array $columns
     * 
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        return $this->model->find($id, $columns);
    }


    /**
     * @param $field
     * @param $value
     * @param array $columns
     * 
     * @return mixed
     */
    public function findBy($field, $value, $columns = array('*'))
    {
        return $this->model->where($field, '=', $value)->first($columns);
    }


    /**
     * @param int $perPage
     * @param array $columns
     * 
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*'))
    {
        return $this->model->paginate($perPage, $columns);
    }


    /**
     * @param array $data
     * 
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }


    /**
     * @param array $data
     * @param string $field
     * @param string $value
     * 
     * @return mixed
     */
    public function update(array $data, $field, $value)
    {
        return $this->model->where($field, '=', $value)->update($data);
    }


    /**
     * @param $id
     * 
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
