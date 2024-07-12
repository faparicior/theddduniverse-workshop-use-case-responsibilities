<?php
declare(strict_types=1);

namespace Demo\App\Common\Application;

use Demo\App\Common\Exceptions\BoundedContextException;

abstract class ApplicationException extends BoundedContextException
{
}
