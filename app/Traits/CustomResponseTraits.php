<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

trait CustomResponseTraits
{
    /**
     * Success Response api base
     * @param array $data
     * @param string $status
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse(string $message = '', array $data = [], int $code = HttpResponse::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Error Response api base
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */

    public function errorResponse(string $message = '', array $data = [], int $code = HttpResponse::HTTP_NOT_FOUND): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

}
