<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Lang;
use Throwable;

class RoleAdminException extends Exception
{

    public function __construct (mixed $message = null, int $code = 0, $previous = null)
    {
        $message = Lang::get('message.not_have_role');
        parent::__construct((string)$message, $code, $previous);
    }
}
