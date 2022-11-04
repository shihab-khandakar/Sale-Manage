<?php
namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ResponseTrait {


    /**
     * @param int $code
     * @param string $message
     * @param array $data
     * @return JsonResponse
     */
    public function successResponse($code = 200, string $message = "Success", $data = []) :JsonResponse
    {
        return response()->json([
           'status' => $code,
           'message' => $message,
           'data' => $data,
        ], $code);
    }

    public function warningResponse($code = 406, string $message = "Warning") :JsonResponse
    {
        return response()->json([
           'status' => $code,
           'message' => $message,
        ], $code);
    }

    /**
     * @param int $code
     * @param string $message
     * @param array $data
     * @return JsonResponse
     */
    public function errorResponse(int $code = 500, string $message = "Something wont wrong", array $data = []) :JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}