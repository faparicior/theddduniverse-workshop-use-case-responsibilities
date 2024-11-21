<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Application\Command\SignUpMember;

use Demo\App\Advertisements\Shared\ValueObjects\CivicCenterId;
use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\AdminWithIncorrectCivicCenterException;
use Demo\App\Advertisements\User\Domain\Exceptions\MemberAlreadyExistsException;
use Demo\App\Advertisements\User\Domain\Exceptions\UserNotFoundException;
use Demo\App\Advertisements\User\Domain\MemberUser;
use Demo\App\Advertisements\User\Domain\UserRepository;
use Demo\App\Advertisements\User\Domain\ValueObjects\MemberNumber;
use Demo\App\Advertisements\User\Domain\ValueObjects\Role;
use Exception;

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
        // TODO: Use security service
        $adminUser = $this->userRepository->findAdminById(new UserId($command->securityUserId));
        if (!$adminUser) {
            throw UserNotFoundException::asAdmin();
        }

        if (!$adminUser->civicCenterId()->equals(new CivicCenterId($command->civicCenterId))) {
            throw AdminWithIncorrectCivicCenterException::differentCivicCenterFromMember();
        }

        if ($this->userRepository->findMemberByIdOrNull(new UserId($command->memberId))) {
            throw MemberAlreadyExistsException::build();
        }

        $member = MemberUser::signUp(
            new UserId($command->memberId),
            new Email($command->email),
            Password::fromPlainPassword($command->password),
            Role::MEMBER,
            new MemberNumber($command->memberNumber),
            new CivicCenterId($command->civicCenterId),
        );

        $this->userRepository->saveMember($member);
    }
}
