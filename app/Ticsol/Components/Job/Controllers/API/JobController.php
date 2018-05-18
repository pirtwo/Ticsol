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
        $this->data = [
            'job 1' => ['id' => 1, 'title' => 'Frontend Developer'],
            'job 2' => ['id' => 2, 'title' => 'Find Requirement'],
            'job 3' => ['id' => 3, 'title' => 'Css Styling'],
            'job 4' => ['id' => 4, 'title' => 'Backend Developer'],
            'job 5' => ['id' => 5, 'title' => 'Testing'],
            'job 6' => ['id' => 6, 'title' => 'Debugging'],
            'job 7' => ['id' => 7, 'title' => 'Database Managment'],
        ];
    }
    
    public function get(){        
        return response()->json($this->data, 201);        
    }
}