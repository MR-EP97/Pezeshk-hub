<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
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
    public function successResponse(array $data = [], string $status = 'success', int $code = HttpResponse::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
        ], $code);
    }

    /**
     * Error Response api base
     * @param array $data
     * @param string $status
     * @param int $code
     * @return JsonResponse
     */

    public function errorResponse(array $data = [], string $status = 'error', int $code = HttpResponse::HTTP_NOT_FOUND): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
        ], $code);
    }

}
