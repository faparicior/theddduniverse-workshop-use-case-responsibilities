<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Application\Command\DisableMember;

readonly class DisableMemberCommand
{
    public function __construct(
        public string $securityUserId,
        public string $securityUserRole,
        public string $memberId,
    ){}
}
