<?php

namespace App;

trait ApiResponse
{
    protected function successResponse($data = null, $message = 'success', $code = 200)
    {
        return response()->json([
            'success'   => true,
            'message'   => $message,
            'data'      => $data,
        ], $code);
    }

    protected function errorResponse($message, $code = 400)
    {
        if(!is_int($code) || $code < 100 || $code > 599)
        {
            $code = 400;
        }

        return response()->json([
            'success'   =>  false,
            'message'   =>  $message,
            'errors'    =>  (object)[null],
        ], $code);
    }

    protected function errorValidation($errors, $code = 422)
    {
        return response()->json([
            'success'   =>  false,
            'message'   =>  'Validation error',
            'errors'    =>  $errors,
        ], $code);
    }
}
