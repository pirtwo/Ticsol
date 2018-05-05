<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Http\Request;

interface ICaptcha
{
    public function Verify(Request $request);    
    public function GetErrors();
    public static function Widget($theme, $callback = null);
}
