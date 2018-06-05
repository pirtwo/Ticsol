<?php

namespace App\Ticsol\Components\Job\Exceptions;

use Exception;

class JobNotFound extends Exception
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
                'message' => 'The id you are looking for could not be found.',
            ], 404);
    }
}
