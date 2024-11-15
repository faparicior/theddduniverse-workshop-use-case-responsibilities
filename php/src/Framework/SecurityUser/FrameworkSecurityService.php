<?php
declare(strict_types=1);

namespace Demo\App\Framework\SecurityUser;

use Demo\App\Framework\FrameworkRequest;

final class FrameworkSecurityService
{
    public function __construct(private SecurityUserRepository $securityUserRepository)
    {
    }

    public function getSecurityUserFromRequest(FrameworkRequest $request): ?SecurityUser
    {
        return $this->securityUserRepository->findUserById($request->headers()['userSession']);
    }
}
