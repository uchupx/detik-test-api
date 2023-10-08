<?php
namespace App\Exception;

class InvalidBodyException extends \Exception

{
    public function __construct($message = 'Invalid Body', $code = 400)
    {
        parent::__construct($message, $code);
    }
}
