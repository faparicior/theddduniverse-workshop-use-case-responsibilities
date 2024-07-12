<?php
declare(strict_types=1);

namespace Demo\App\Common\Domain;

use Demo\App\Common\Exceptions\BoundedContextException;

abstract class DomainException extends BoundedContextException
{
}
