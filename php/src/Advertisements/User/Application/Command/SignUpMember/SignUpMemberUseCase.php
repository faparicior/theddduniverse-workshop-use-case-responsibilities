<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Application\Command\SignUpMember;

use Demo\App\Advertisements\CivicCenter\Domain\ValueObjects\CivicCenterId;
use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Demo\App\Advertisements\User\Domain\MemberUser;
use Demo\App\Advertisements\User\Domain\UserRepository;
use Demo\App\Advertisements\User\Domain\ValueObjects\MemberNumber;
use Demo\App\Advertisements\User\Domain\ValueObjects\Role;
use Demo\App\Advertisements\User\Domain\ValueObjects\UserId;
use Exception;
use const Chemem\Bingo\Functional\equals;

final class SignUpMemberUseCase
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(SignUpMemberCommand $command): void
    {
        $admin = $this->userRepository->findAdminById(new UserId($command->managerId));
        if (!$admin) {
            throw new Exception('Admin not found');
        }

        //TODO: Use equals
        if ($admin->civicCenterId()->value() !== $command->civicCenterId) {
            throw new Exception('Admin does not belong to the same civic center');
        }

        if ($this->userRepository->findMemberById(new UserId($command->id))) {
            throw new Exception('Member already exists');
        }

        $member = new MemberUser(
            new UserId($command->id),
            new Email($command->email),
            Password::fromPlainPassword($command->password),
            Role::MEMBER,
            new MemberNumber($command->memberNumber),
            new CivicCenterId($command->civicCenterId),
        );

        $this->userRepository->saveMember($member);

    }
}
