<?php


namespace App\Exceptions;

use App\Exceptions\Code;
use App\Traits\Response;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionReport
{
    use Response;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Exception
     */
    protected $exception;

    protected $report;

    protected $doReport = [
        AuthenticationException::class => ['code' => Code::AUTHENTICATE, 'statusCode' => 401],
        AuthorizationException::class => ['code' => Code::AUTHORIZATION, 'statusCode' => 403],
        NotFoundHttpException::class => ['code' => Code::RESOURCE_NOT_FOUND, 'statusCode' => 404],
        ModelNotFoundException::class => ['code' => Code::MODEL_NOT_FOUND, 'statusCode' => 404],
        MethodNotAllowedHttpException::class => ['code' => Code::METHOD_NOT_ALLOW, 'statusCode' => 405],
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

    /**
     * Convert the given exception to an array.
     *
     * @param  Exception  $e
     * @return array
     */
    public static function convertExceptionToArray(Exception $e)
    {
        return config('app.debug', false) ? [
            'message' => $e->getMessage(),
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ] : null;
    }

    public function report()
    {
        $exceptionItem = $this->doReport[$this->report];
        $code          = $exceptionItem['code'];
        $statusCode    = isset($exceptionItem['statusCode']) ? $exceptionItem['statusCode'] : $this->exception->getCode();
        $message       = isset($exceptionItem['message']) ? __($exceptionItem['message']) : __($this->exception->getMessage());

        return $this->fail($code, $message, $statusCode);
    }
}
