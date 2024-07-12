<?php
declare(strict_types=1);

namespace Demo\App\Common\Exceptions;

use Throwable;

abstract class BoundedContextException extends \Exception
{
    protected function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
