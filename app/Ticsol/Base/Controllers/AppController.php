<?php

namespace App\Ticsol\Base\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppController extends Controller
{
    public function index()
    {
        return view('app');
    }
}
