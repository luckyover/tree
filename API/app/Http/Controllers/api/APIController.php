<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;

class APIController extends Controller
{
    /**
     * handleSuccess
     *
     * @param  Array $data
     * @param  Integer $http_code
     * @return void
     */
    public function handleApiSuccess($data = [], $http_code = 200)
    {
        return response()->json($data, $http_code);
    }

    /**
     * handleError
     *
     * @param  String $message
     * @param  Integer $http_code
     * @return void
     */
    public function handleApiError($message = '', $payload = [], $http_code = 501)
    {
        $response = [
            'message' => $message,
            'errors' => $payload,
            'status' =>$http_code
        ];
        return response()->json($response);
    }
}
