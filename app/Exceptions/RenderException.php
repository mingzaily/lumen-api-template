<?php

namespace App\Exceptions;

use App\Exceptions\Code;
use App\Traits\Response;
use Exception;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class RenderException extends Exception
{
    use Response;

    protected $statusCode;

    public function __construct($code = Code::OwnServer, $message = 'Server Error', $statusCode = HttpResponse::HTTP_INTERNAL_SERVER_ERROR)
    {
        $this->statusCode = $statusCode;
        parent::__construct($message, $code);
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * 自定义异常报告均为JSON响应
     */
    public function render()
    {
        return $this->fail(
            $this->getCode(),
            $this->getMessage(),
            $this->getStatusCode(),
            ExceptionReport::convertExceptionToArray($this)
        );
    }
}
