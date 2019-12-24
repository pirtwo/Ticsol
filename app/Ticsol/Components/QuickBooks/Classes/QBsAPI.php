<?php

namespace App\Ticsol\Components\QuickBooks\Classes;

use App\Ticsol\Components\QuickBooks\Classes\QBsAuth;
use QuickBooksOnline\API\Core\Http\Serialization\JsonObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;
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

    public function createCustomer($data)
    {
        $obj = Customer::create($data);
        $res = $this->dataService->Add($obj);
    }

    /**
     * creates a resource in QBs API by its name, exp: create('Customer', $data).
     * 
     * @return object
     */
    public function create($className, $data)
    {
        $class = $this->getFullyQualifiedName($className);

        $obj = $class::create($data);
        $respond = $this->dataService->Add($obj);

        $error = $this->dataService->getLastError();

        return (object)["error" => $error, "respond" => $respond];
    }

    /**
     * updates a resource in QBs API by its name, exp: update('Customer', $data, $id).
     * 
     * @return object
     */
    public function update($className, $data, $id)
    {
        $class = $this->getFullyQualifiedName($className);

        $obj = $this->dataService->findbyId($className, $id);
        $obj = $class::update($obj, $data);
        $respond = $this->dataService->Update($obj);

        $error = $this->dataService->getLastError();

        return (object)["error" => $error, "respond" => $respond];
    }

    // ======== Helpers =========

    protected function select($entityName, $columns = [], $condition = null)
    {
        $columns = sizeof($columns) > 0 ? implode(",", $columns) : "*";
        $condition = $condition != "" ? "WHERE {$condition}" : "";

        return "SELECT {$columns} FROM {$entityName} {$condition}";
    }

    /**
     * returns fully qualified name of QBs class name.
     * 
     * @return string
     */
    private function getFullyQualifiedName($className)
    {
        return "\\QuickBooksOnline\\API\\Facades\\" . $className;
    }
}
