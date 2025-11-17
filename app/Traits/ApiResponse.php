<?php

namespace App\Traits;

trait ApiResponse
{
    public function ResponseSuccess($data = [], $message = "Success")
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ]);
    }

    public function ResponseError($message = "Error")
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ]);
    }
}
