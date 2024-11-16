<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\UI\Http;

use Demo\App\Advertisements\User\Application\Command\DisableMember\DisableMemberCommand;
use Demo\App\Advertisements\User\Application\Command\DisableMember\DisableMemberUseCase;
use Demo\App\Advertisements\User\Application\Command\EnableMember\EnableMemberCommand;
use Demo\App\Advertisements\User\Application\Command\EnableMember\EnableMemberUseCase;
use Demo\App\Common\Exceptions\BoundedContextException;
use Demo\App\Common\UI\CommonController;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Demo\App\Framework\SecurityUser\FrameworkSecurityService;

final class EnableMemberController extends CommonController
{
    public function __construct(
        private EnableMemberUseCase $useCase,
        private FrameworkSecurityService $securityService,
    ) {}

    public function request(FrameworkRequest $request, array $pathValues = []): FrameworkResponse
    {
        $user = $this->securityService->getSecurityUserFromRequest($request);

        if (null == $user || !$user->role() == 'admin') {
            return $this->processUnauthorizedResponse();
        }

        try {
            $command = new EnableMemberCommand(
                $user->id(),
                $user->role(),
                $pathValues['memberId'],
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
