<?php

namespace Decodeblock\Ercaspay\Exceptions;

use Exception;

class ErcaspayRequestException extends Exception
{
    protected $response;

    public function __construct(string $message, int $code = 0, $response = null, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
