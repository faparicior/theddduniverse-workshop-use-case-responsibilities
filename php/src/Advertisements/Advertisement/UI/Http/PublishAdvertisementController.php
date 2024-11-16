<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\UI\Http;

use Demo\App\Advertisements\Advertisement\Application\Command\PublishAdvertisement\PublishAdvertisementCommand;
use Demo\App\Advertisements\Advertisement\Application\Command\PublishAdvertisement\PublishAdvertisementUseCase;
use Demo\App\Common\Exceptions\BoundedContextException;
use Demo\App\Common\UI\CommonController;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;

final class PublishAdvertisementController extends CommonController
{
    public function __construct(private PublishAdvertisementUseCase $useCase)
    {
    }

    public function request(FrameworkRequest $request, array $pathValues = []): FrameworkResponse
    {
        try {
            $command = new PublishAdvertisementCommand(
                ($request->content())['id'],
                ($request->content())['description'],
                ($request->content())['email'],
                ($request->content())['password'],
                ($request->content())['memberId'],
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
