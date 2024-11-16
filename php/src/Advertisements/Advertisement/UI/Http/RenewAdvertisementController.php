<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\UI\Http;

use Demo\App\Advertisements\Advertisement\Application\Command\RenewAdvertisement\RenewAdvertisementCommand;
use Demo\App\Advertisements\Advertisement\Application\Command\RenewAdvertisement\RenewAdvertisementUseCase;
use Demo\App\Common\Exceptions\BoundedContextException;
use Demo\App\Common\UI\CommonController;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;

final class RenewAdvertisementController extends CommonController
{
    public function __construct(private RenewAdvertisementUseCase $useCase)
    {
    }

    public function request(FrameworkRequest $request, array $pathValues = []): FrameworkResponse
    {
        try {
            $command = new RenewAdvertisementCommand(
                $request->getIdPath(),
                ($request->content())['password'],
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
