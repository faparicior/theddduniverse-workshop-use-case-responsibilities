<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\EnableAdvertisement;

use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Services\AdvertisementSecurityService;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\UserRepository;
use Demo\App\Framework\Database\TransactionManager;
use Exception;

final class EnableAdvertisementUseCase
{
    public function __construct(
        private AdvertisementRepository      $advertisementRepository,
        private UserRepository               $userRepository,
        private AdvertisementSecurityService $securityService,
        private TransactionManager           $transactionManager,
    ) {}

    /**
     * @throws Exception
     */
    public function execute(EnableAdvertisementCommand $command): void
    {
        $this->transactionManager->beginTransaction();

        try {
            $advertisement = $this->advertisementRepository->findByIdOrFail(new AdvertisementId($command->advertisementId));

            $this->securityService->verifyAdminUserCanManageAdvertisement(
                new UserId($command->securityUserId),
                $advertisement,
            );

            $member = $this->userRepository->findMemberByIdOrFail($advertisement->memberId());

            $activeAdvertisements = $this->advertisementRepository->activeAdvertisementsByMemberId($member->id());

            if ($activeAdvertisements->value() >= 3) {
                throw new Exception('Member has 3 active advertisements');
            }

            $advertisement->enable();

            $this->advertisementRepository->save($advertisement);
        } catch (Exception $exception) {
            $this->transactionManager->rollback();
            throw $exception;
        }
    }
}
