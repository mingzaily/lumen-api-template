<?php


namespace App\Exceptions;

use App\Exceptions\Code;
use App\Traits\Response;
use Exception;
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
        AuthenticationException::class => ['code' => Code::Authenticate, 'statusCode' => 401],
        NotFoundHttpException::class => ['code' => Code::NotFound, 'statusCode' => 404],
        ModelNotFoundException::class => ['code' => Code::ModelNotFound, 'statusCode' => 404],
        MethodNotAllowedHttpException::class => ['code' => Code::MethodNotFound, 'statusCode' => 405],
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
        $message       = isset($exceptionItem['message']) ? $exceptionItem['message'] : $this->exception->getMessage();
        $statusCode    = isset($exceptionItem['statusCode']) ? $exceptionItem['statusCode'] : $this->exception->getCode();

        return $this->fail($code, $message, $statusCode);
    }
}
