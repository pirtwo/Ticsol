<?php

namespace App\Ticsol\Base\Exceptions;

use Exception;

class NotFound extends Exception
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
                'code' => 404,
                'message' => 'The resource you are looking for could not be found.',
            ], 404);
    }
}
