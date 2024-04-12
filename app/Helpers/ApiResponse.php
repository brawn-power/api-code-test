<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data, $message = null, $statusCode = 200)
    {
        $response = [
            'success' => true,
            'data' => $data,
        ];

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, $statusCode);
    }

    public static function error($message, $statusCode)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        return response()->json($response, $statusCode);
    }
}