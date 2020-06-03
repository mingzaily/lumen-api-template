<?php

namespace App\Exceptions;

use Exception;

class RenderException extends Exception
{
    protected $status;

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
}
