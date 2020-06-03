<?php

namespace App\Exceptions;

use App\Constants\StatusConstant;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use App\Traits\Response;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
        HttpException::class,
        ModelNotFoundException::class,
        NotFoundHttpException::class,
        MethodNotAllowedHttpException::class,
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
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param Exception $exception
     * @return Response|JsonResponse
     *
     * @throws Throwable
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    protected function prepareJsonResponse($request, Exception $exception)
    {
        // ajax请求
        // 需要自定义处理的框架异常
        if ($report = ExceptionReport::shouldReport($request, $exception)) {
            return $report->report();
        }
        // 无法预计的异常和自定义错误（继承RenderException），检查开启debug决定是否对外暴露错误
        return $this->fail(
            $exception instanceof RenderException ? $exception->getStatus() : StatusConstant::ServerError,
            $exception instanceof RenderException ? $exception->getMessage() : 'Server Error',
            $exception instanceof RenderException ? $exception->getCode() : HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
            env('APP_DEBUG') ? $this->convertExceptionToArray($exception) : null
        );
    }
}
