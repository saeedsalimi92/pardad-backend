<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function apiResponse(bool $success = true,
                                string $message = "",
                                array|object $data = [],
                                $paginationData = [],
                                int $httpStatusCode = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'pagination_info' => $paginationData
        ], $httpStatusCode);
    }
}
