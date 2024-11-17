<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\UI\Http;

use Demo\App\Advertisements\Advertisement\Application\Command\DisableAdvertisement\DisableAdvertisementCommand;
use Demo\App\Advertisements\Advertisement\Application\Command\DisableAdvertisement\DisableAdvertisementUseCase;
use Demo\App\Common\Exceptions\BoundedContextException;
use Demo\App\Common\UI\CommonController;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Demo\App\Framework\SecurityUser\FrameworkSecurityService;

final class DisableAdvertisementController extends CommonController
{
    public function __construct(
        private DisableAdvertisementUseCase $useCase,
        private FrameworkSecurityService $securityService,
    ) {}

    public function request(FrameworkRequest $request, array $pathValues = []): FrameworkResponse
    {
        try {
            $user = $this->securityService->getSecurityUserFromRequest($request);

            if (null == $user || !$user->role() == 'admin') {
                return $this->processUnauthorizedResponse();
            }

            $command = new DisableAdvertisementCommand(
                $user->id(),
                $user->role(),
                $pathValues['advertisementId'],
            );

            $this->useCase->execute($command);

            return $this->processSuccessfulCommand();
        } catch (BoundedContextException $exception) {
            return $this->processDomainOrApplicationExceptionResponse($exception);
        } catch (\Throwable $exception) {
            return $this->processGenericException($exception);
        }
    }
}
