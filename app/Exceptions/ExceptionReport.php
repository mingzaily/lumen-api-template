<?php


namespace App\Exceptions;

use App\Constants\StatusConstant;
use App\Traits\Response;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionReport
{
    use Response;

    protected $request;

    protected $exception;

    protected $report;

    protected $doReport = [
        AuthenticationException::class => ['status' => StatusConstant::AuthError, 'message' => 'Token is Invalid', 'code' => 401],
        NotFoundHttpException::class => ['status' => StatusConstant::NotFoundError, 'message' => 'Not Found', 'code' => 404],
        MethodNotAllowedHttpException::class => ['status' => StatusConstant::NotFoundError, 'message' => 'Method Not Allow', 'code' => 405],
    ];

    public function __construct(Request $request, Exception $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
    }

    public static function shouldReport(Request $request, Exception $exception)
    {
        $exceptionReport = new ExceptionReport($request, $exception);
        $exceptionItems = array_keys($exceptionReport->doReport);
        foreach ($exceptionItems as $item) {
            if ($exceptionReport->exception instanceof $item) {
                $exceptionReport->report = $item;
                return $exceptionReport;
            }
        }
        return null;
    }

    public function report()
    {
        $exceptionItem = $this->doReport[$this->report];
        $status = $exceptionItem['status'];
        $message = $exceptionItem['message'];
        $code = $exceptionItem['code'];
        return $this->fail($status, $message, $code);
    }
}
