<?php
namespace App\Domain\Exceptions;

use Exception;


class DuplicateProfesorException extends Exception
{
    public function __construct(string $message = "Profesor duplicado por email o documento.", int $code = 409)
    {
        parent::__construct($message, $code);
    }
}
