<?php

namespace App\Ticsol\Components\QuickBooks\Classes;

use App\Ticsol\Components\QuickBooks\Classes\QBsAuth;
use QuickBooksOnline\API\Core\Http\Serialization\JsonObjectSerializer;

class QBsAPI
{
    protected $auth;
    protected $dataService;
    protected $jsonObjSerializer;

    public function __construct(QBsAuth $auth)
    {
        $this->auth = $auth;
        $this->dataService = $auth->getDataService();
        $this->jsonObjSerializer = new JsonObjectSerializer();
    }

    public function getCompanyInfo()
    {
        return $this->dataService->getCompanyInfo();
    }

    public function getEmployees($columns = [], $condition = "")
    {
        $query = $this->select("Employee", $columns, $condition);

        $res = $this->dataService->Query($query);

        return $res;
    }

    public function getVendors($columns = [], $condition = "")
    {
        $query = $this->select("Vendor", $columns, $condition);

        $res = $this->dataService->Query($query);

        return $res;
    }

    public function getClasses($columns = [], $condition = "")
    {
        $query = $this->select("Class", $columns, $condition);

        $res = $this->dataService->Query($query);

        return $res;
    }

    public function getCustomers($columns = [], $condition = "")
    {
        $query = $this->select("Customer", $columns, $condition);

        $res = $this->dataService->Query($query);

        return $res;
    }

    public function getDepartments($columns = [], $condition = "")
    {
        $query = $this->select("Department", $columns, $condition);

        $res = $this->dataService->Query($query);

        return $res;
    }

    protected function select($entityName, $columns = [], $condition = null)
    {
        $columns = sizeof($columns) > 0 ? implode(",", $columns) : "*";
        $condition = $condition != "" ? "WHERE {$condition}" : "";

        return "SELECT {$columns} FROM {$entityName}" . $condition;
    }

    protected function update()
    {
        //
    }

    protected function delete()
    {
        //
    }

}
