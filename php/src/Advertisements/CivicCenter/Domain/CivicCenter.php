<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\CivicCenter\Domain;

use Demo\App\Advertisements\CivicCenter\Domain\ValueObjects\CivicCenterId;
use Demo\App\Advertisements\CivicCenter\Domain\ValueObjects\CivicCenterName;

final class CivicCenter
{
    public function __construct(private readonly CivicCenterId $id, private CivicCenterName $name)
    {
    }

    public function id(): CivicCenterId
    {
        return $this->id;
    }

    public function name(): CivicCenterName
    {
        return $this->name;
    }
}
