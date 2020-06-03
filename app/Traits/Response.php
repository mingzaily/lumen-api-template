<?php

namespace App\Traits;

use App\Constants\StatusConstant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;

Trait Response
{
    /**
     * @param int $status
     * @param string $message
     * @param int $code
     * @param null $data
     */
    public function fail(int $status, $message = '', int $code = HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,// 错误描述
            'data' => $data,// 错误详情
        ], $code)->throwResponse();
    }

    // 请求错误
    public function errorBadRequest($message = 'Bad Request')
    {
        return $this->fail(StatusConstant::BadError, $message, HttpResponse::HTTP_BAD_REQUEST);
    }

    // 鉴权错误
    public function errorForbidden($message = 'Forbidden')
    {
        return $this->fail(StatusConstant::ForbiddenError, $message, HttpResponse::HTTP_FORBIDDEN);
    }

    // 认证错误
    public function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->fail(StatusConstant::AuthError, $message, HttpResponse::HTTP_UNAUTHORIZED);
    }

    // 数据校验错误
    public function errorUnprocessableEntity($message = 'Parameter Validation is Invalid')
    {
        return $this->fail(StatusConstant::ValidateError, $message, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param array|null $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function success($data = null, string $message = 'Success', $code = HttpResponse::HTTP_OK)
    {
        return response()->json($sendData = [
            'status' => StatusConstant::Success,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function created($data = null, $message = 'Created')
    {
        return $this->success($data, $message, HttpResponse::HTTP_CREATED);
    }

    public function noContent($message = 'No Content')
    {
        return $this->success(null, $message, HttpResponse::HTTP_NO_CONTENT);
    }

    public function accepted($message = 'Accepted')
    {
        return $this->success(null, $message, HttpResponse::HTTP_ACCEPTED);
    }
}
