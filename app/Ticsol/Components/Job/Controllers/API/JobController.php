<?php

namespace App\Ticsol\Components\Controllers\API;

use App\Http\Controllers\Controller;
use App\Ticsol\Components\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JobController extends Controller{
    
    private $data;

    public function __construct()
    {
        $this->data = array(
            ['id' => 1, 'title' => 'Frontend Developer'],
            ['id' => 2, 'title' => 'Find Requirement'],
            ['id' => 3, 'title' => 'Css Styling'],
            ['id' => 4, 'title' => 'Backend Developer'],
            ['id' => 5, 'title' => 'Testing'],
            ['id' => 6, 'title' => 'Debugging'],
            ['id' => 7, 'title' => 'Database Managment'],
        );
    }
    
    public function get(){        
        return response()->json($this->data, 201);        
    }
}