<?php

namespace App\Exceptions;

use App\Constants\StatusConstant;
use App\Traits\Response;
use Exception;
use Illuminate\Support\Arr;

class RenderException extends Exception
{
    use Response;

    protected $status = StatusConstant::ServerError;

    public function __construct($status, $message = 'Server Error', $code = 500)
    {
        parent::__construct($message, $code);
        $this->setStatus($status);
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * 转换异常为 HTTP 响应
     */
    public function render()
    {
        return $this->fail(
            $this->status,
            $this->getMessage(),
            $this->getCode(),
            ExceptionReport::convertExceptionToArray($this)
        );
    }
}
