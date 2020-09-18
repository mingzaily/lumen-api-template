<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use App\Traits\Response;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use Response;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        AuthenticationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        NotFoundHttpException::class,
        MethodNotAllowedHttpException::class,
        RenderException::class,
        ValidationException::class
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Exception $exception
     * @return void
     *
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        // 自定义异常可以选择注册report函数，针对每个自定义异常，有不同日志报告方式
        parent::report($exception);
    }

    /**
     * @param $request
     * @param Exception $exception
     * @return Response|JsonResponse
     *
     * @throws Exception
     */
    public function render($request, Exception $exception)
    {
        return $request->expectsJson()
            ? $this->prepareJsonResponse($request, $exception)
            : parent::render($request, $exception);
    }

    /**
     * @param Request $request
     * @param Exception $exception
     * @return JsonResponse|HttpStatus
     */
    protected function prepareJsonResponse($request, Exception $exception)
    {
        // ajax请求
        // 需要自定义处理的框架异常
        if ($report = ExceptionReport::shouldReport($request, $exception)) {
            return $report->report();
        }
        // 自定义异常（继承RenderException），已注册reader函数；抛出时写明业务码，错误描述，httpCode
        // 校验异常直接返回由异常生成的结构体即可
        if (method_exists($exception, 'render')) {
            return $exception->render();
        } elseif ($exception instanceof ValidationException) {
            return $exception->getResponse();
        }
        // 既不拦截也不是自定义
        // 无法预计的框架异常，检查开启debug决定是否对外暴露错误
        return $this->fail(
            Code::OwnServer,
            '服务不可用，请稍后尝试',
            HttpStatus::HTTP_INTERNAL_SERVER_ERROR,
            ExceptionReport::convertExceptionToArray($exception)
        );
    }
}
