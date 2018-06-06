<?php

namespace App\Exceptions\Api;

class NotOwnerException extends ApiException
{
    public function __construct($message = null, $statusCode = BAD_REQUEST)
    {
        $message = $message ? $message : translate('exception.not_owner');
        parent::__construct($message, $statusCode);
    }
}
