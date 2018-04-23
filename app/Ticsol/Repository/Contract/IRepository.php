<?php

namespace App\Ticsol\Repository\Contract;

interface IRepository{
    public function all($columns = array('*'));
    public function find($id, $columns = array('*'));
    public function findBy($field, $value, $columns = array('*'));
    public function paginate($perPage = 15, $columns = array('*'));
    public function create(array $data);
    public function update(array $data, $field, $value);
    public function delete($id);
}