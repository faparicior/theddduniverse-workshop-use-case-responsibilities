<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\UI\Http;

use Demo\App\Advertisements\User\Application\Command\SignUpMember\SignUpMemberCommand;
use Demo\App\Advertisements\User\Application\Command\SignUpMember\SignUpMemberUseCase;
use Demo\App\Common\Exceptions\BoundedContextException;
use Demo\App\Common\UI\CommonController;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;

final class SignUpMemberController extends CommonController
{
    public function __construct(private SignUpMemberUseCase $useCase)
    {
    }

    public function request(FrameworkRequest $request): FrameworkResponse
    {
        // get role from manager_id request

        try {
            $command = new SignUpMemberCommand(
                ($request->content())['id'],
                ($request->content())['email'],
                ($request->content())['password'],
                ($request->content())['memberNumber'],
                ($request->content())['civicCenterId'],
                ($request->content())['managerId'],
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
