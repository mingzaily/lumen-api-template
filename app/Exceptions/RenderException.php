<?php

namespace App\Exceptions;

use App\Traits\Response;
use Exception;

class RenderException extends Exception
{
    use Response;

    protected $statusCode;

    public function __construct($message, $code = Code::SYSTEM, $statusCode = 500)
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
            __($this->getMessage()),
            $this->getStatusCode(),
            ExceptionReport::convertExceptionToArray($this)
        );
    }
}
