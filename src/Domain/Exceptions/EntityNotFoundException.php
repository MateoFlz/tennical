<?php
declare(strict_types=1);

namespace App\Domain\Exceptions;

class EntityNotFoundException extends \Exception
{
    public function __construct(string $entity, $id, int $code = 404, \Throwable $previous = null)
    {
        $message = sprintf("No se encontró %s con ID: %s", $entity, $id);
        parent::__construct($message, $code, $previous);
    }
}
