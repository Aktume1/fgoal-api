<?php

namespace App\Exceptions\Api;

class NotFoundException extends ApiException
{
    public function __construct($message = null, $statusCode = NOT_FOUND)
    {
        $message = $message ? $message : translate('exception.not_found');

        parent::__construct($message, $statusCode);
    }
}
