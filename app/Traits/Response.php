<?php

namespace App\Traits;

use App\Exceptions\Code;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

Trait Response
{
    /**
     * @param int $code
     * @param string $message
     * @param int $statusCode
     * @param mixed $data
     * @return JsonResponse
     */
    public function fail(int $code = Code::SYSTEM, string $message = null, int $statusCode = 500, $data = null)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,// 错误描述
            'data' => $data,// 错误详情
        ], $statusCode);
    }

    /**
     * @param array|null $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function success($data = null, string $message = 'OK', int $statusCode = 200)
    {
        return response()->json([
            'code' => Code::SUCCESS,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public function created($data = null, $message = 'Created')
    {
        return $this->success($data, $message, HttpStatus::HTTP_CREATED);
    }
}
