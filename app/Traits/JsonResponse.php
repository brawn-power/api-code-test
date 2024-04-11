<?php

namespace App\Traits;


trait JsonResponse
{
    /**
     * Success Response
     *
     * @param $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data, $statusCode = 200, $headers = [], $options = 0){
        return response()->json([
            'success' => true,
            'data' => $data
        ], $statusCode, $headers, $options);
    }

    /**
     * Error Response
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($message, $statusCode = 400){
        return response()->json([
            'success' => false,
            'error' => $message,
        ], $statusCode);
    }
}
