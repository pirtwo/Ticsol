<?php

namespace App\Ticsol\Components\QuickBooks\Classes;

use App\Ticsol\Components\QuickBooks\Classes\QBsAuth;
use QuickBooksOnline\API\Core\Http\Serialization\JsonObjectSerializer;

use QuickBooksOnline\API\Facades\Vendor;

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

    public function getAccounts($columns = [], $condition = "")
    {
        $query = $this->select("Account", $columns, $condition);

        $res = $this->dataService->Query($query);

        return $res;
    }

    public function createVendor($data)
    {
        $obj = Vendor::create($data);
        $res = $this->dataService->Add($obj);

        return $res;
    }


    // ======== Helpers =========

    protected function select($entityName, $columns = [], $condition = null)
    {
        $columns = sizeof($columns) > 0 ? implode(",", $columns) : "*";
        $condition = $condition != "" ? "WHERE {$condition}" : "";

        return "SELECT {$columns} FROM {$entityName} {$condition}";
    }

    protected function create()
    {

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
