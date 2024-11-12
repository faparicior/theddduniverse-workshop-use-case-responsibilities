<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Application\Command\SignUpMember;

use Demo\App\Advertisements\User\Domain\UserRepository;
use Demo\App\Advertisements\User\Domain\ValueObjects\UserId;
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
        $user = $this->userRepository->findAdminById(new UserId($command->id));
        // Find user
        // Verify if member exists in the civic center
        // Create member with civic center
        // Save member
    }
}
