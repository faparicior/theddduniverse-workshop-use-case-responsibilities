<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Application\Command\DisableMember;

use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\AdminWithIncorrectCivicCenterException;
use Demo\App\Advertisements\User\Domain\Exceptions\MemberDoesNotExistsException;
use Demo\App\Advertisements\User\Domain\Exceptions\UserNotFoundException;
use Demo\App\Advertisements\User\Domain\UserRepository;
use Exception;

final class DisableMemberUseCase
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(DisableMemberCommand $command): void
    {
        // TODO: Use security service
        $adminUser = $this->userRepository->findAdminById(new UserId($command->securityUserId));
        if (!$adminUser) {
            throw UserNotFoundException::asAdmin();
        }
        $member = $this->userRepository->findMemberByIdOrFail(new UserId($command->memberId));

        if (null === $member) {
            throw MemberDoesNotExistsException::build();
        }

        if (!$adminUser->civicCenterId()->equals($member->civicCenterId())) {
            throw AdminWithIncorrectCivicCenterException::differentCivicCenterFromMember();
        }

        $member->disable();

        $this->userRepository->saveMember($member);
    }
}
