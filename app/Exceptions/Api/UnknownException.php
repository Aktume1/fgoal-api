<?php

namespace App\Exceptions\Api;

class UnknownException extends ApiException
{
    public function __construct($message = null, $statusCode = BAD_REQUEST)
    {
        $message = $message ? $message : translate('exception.unknown_error');

        parent::__construct($message, $statusCode);
    }
}
