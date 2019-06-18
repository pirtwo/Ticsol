<?php

namespace App\Ticsol\Base\Exceptions;

use Exception;

class InvalidCriteriaException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json(
            [
                'error' => true,
                'code' => 1007,
                'message' => 'Invalid query or criteria format.',
            ], 400);
    }
}
