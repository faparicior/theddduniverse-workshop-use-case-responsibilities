<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Application\Command\SignUpMember;

readonly class SignUpMemberCommand
{
    public function __construct(
        public string $userId,
        public string $userRole,
        public string $id,
        public string $email,
        public string $password,
        public string $memberNumber,
        public string $civicCenterId,
    ){}
}
