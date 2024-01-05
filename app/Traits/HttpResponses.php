<?php

namespace App\Traits;

trait HttpResponses {
    
    protected function success($data, string $message = 'Success!', int $code = 200)
    {
        return response()->json([
            'status' => '00',
            'message' => $message,
            'data' => $data
        ], $code);
    }
    
    protected function error($data, string $message = null, int $code)
    {
        return response()->json([
            'status' => 'An error has occurred...',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}