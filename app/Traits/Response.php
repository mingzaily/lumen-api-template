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
    public function fail(int $code = Code::OwnServer, string $message = null, int $statusCode = HttpStatus::HTTP_INTERNAL_SERVER_ERROR, $data = null)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,// 错误描述
            'data' => $data,// 错误详情
        ], $statusCode);
    }

    // 数据校验错误
    public function errorUnprocessableEntity($message = 'Unprocessable Entity')
    {
        return $this->fail(Code::Validate, $message, HttpStatus::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param array|null $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function success($data = null, string $message = 'OK', $statusCode = HttpStatus::HTTP_OK)
    {
        return response()->json([
            'code' => Code::Success,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public function created($data = null, $message = 'Created')
    {
        return $this->success($data, $message, HttpStatus::HTTP_CREATED);
    }

    public function noContent($message = 'No Content')
    {
        return $this->success(null, $message, HttpStatus::HTTP_NO_CONTENT);
    }

    public function accepted($message = 'Accepted')
    {
        return $this->success(null, $message, HttpStatus::HTTP_ACCEPTED);
    }
}
