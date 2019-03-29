<?php

namespace Carpentree\Core\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class EmailNotVerified extends HttpException
{
    public static function create()
    {
        $message = __("Your email is not verified");

        return new static(403, $message);
    }
}
