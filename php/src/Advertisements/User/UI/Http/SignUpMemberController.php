<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\UI\Http;

use Demo\App\Advertisements\User\Application\Command\SignUpMember\SignUpMemberCommand;
use Demo\App\Advertisements\User\Application\Command\SignUpMember\SignUpMemberUseCase;
use Demo\App\Common\Exceptions\BoundedContextException;
use Demo\App\Common\UI\CommonController;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;
use Demo\App\Framework\SecurityUser\FrameworkSecurityService;

final class SignUpMemberController extends CommonController
{
    public function __construct(
        private SignUpMemberUseCase $useCase,
        private FrameworkSecurityService $securityService,
    ) {}

    public function request(FrameworkRequest $request): FrameworkResponse
    {
        // get role from manager_id request
        $user = $this->securityService->getSecurityUserFromRequest($request);

        if (null == $user || !$user->role() == 'admin') {
            return $this->processUnauthorizedResponse();
        }

        try {
            $command = new SignUpMemberCommand(
                $user->id(),
                $user->role(),
                ($request->content())['id'],
                ($request->content())['email'],
                ($request->content())['password'],
                ($request->content())['memberNumber'],
                ($request->content())['civicCenterId'],
            );

            $this->useCase->execute($command);

            return $this->processSuccessfulCreateCommand();
        } catch (BoundedContextException $exception) {
            return $this->processDomainOrApplicationExceptionResponse($exception);
        } catch (\Throwable $exception) {
            return $this->processGenericException($exception);
        }
    }
}
