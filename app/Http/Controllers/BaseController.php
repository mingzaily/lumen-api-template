<?php

namespace App\Http\Controllers;

use App\Traits\Response;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as Controller;

class BaseController extends Controller
{
    use Response;

    /**
     * Custom Failed Validation Response
     *
     * @param  Request $request
     * @param  array  $errors
     * @return mixed
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        if (isset(static::$responseBuilder)) {
            return call_user_func(static::$responseBuilder, $request, $errors);
        }
        return $this->errorUnprocessableEntity(array_shift($errors)[0]);
    }
}
