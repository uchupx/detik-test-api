<?php

namespace App\Exception;

class NotFoundException extends \Exception

{
    public function __construct($message = 'Not Found', $code = 400)
    {
        parent::__construct($message, $code);
    }
}
