<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\UI\Http;

use Demo\App\Advertisements\Advertisement\Application\Command\DisableAdvertisement\DisableAdvertisementCommand;
use Demo\App\Advertisements\Advertisement\Application\Command\DisableAdvertisement\DisableAdvertisementUseCase;
use Demo\App\Advertisements\Advertisement\Application\Command\EnableAdvertisement\EnableAdvertisementCommand;
use Demo\App\Advertisements\Advertisement\Application\Command\EnableAdvertisement\EnableAdvertisementUseCase;
use Demo\App\Common\Exceptions\BoundedContextException;
use Demo\App\Common\UI\CommonController;
use Demo\App\Framework\FrameworkRequest;
use Demo\App\Framework\FrameworkResponse;

final class EnableAdvertisementController extends CommonController
{
    public function __construct(private EnableAdvertisementUseCase $useCase)
    {
    }

    public function request(FrameworkRequest $request, array $pathValues = []): FrameworkResponse
    {
        try {
            $command = new EnableAdvertisementCommand(
                $pathValues['advertisementId'],
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
