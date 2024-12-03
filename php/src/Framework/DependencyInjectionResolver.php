<?php
declare(strict_types=1);

namespace Demo\App\Framework;

use Demo\App\Advertisements\Advertisement\Application\Command\ApproveAdvertisement\ApproveAdvertisementUseCase;
use Demo\App\Advertisements\Advertisement\Application\Command\DeleteAdvertisement\DeleteAdvertisementUseCase;
use Demo\App\Advertisements\Advertisement\Application\Command\DisableAdvertisement\DisableAdvertisementUseCase;
use Demo\App\Advertisements\Advertisement\Application\Command\EnableAdvertisement\EnableAdvertisementUseCase;
use Demo\App\Advertisements\Advertisement\Application\Command\PublishAdvertisement\PublishAdvertisementUseCase;
use Demo\App\Advertisements\Advertisement\Application\Command\RenewAdvertisement\RenewAdvertisementUseCase;
use Demo\App\Advertisements\Advertisement\Application\Command\UpdateAdvertisement\UpdateAdvertisementUseCase;
use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Services\AdvertisementSecurityService;
use Demo\App\Advertisements\Advertisement\Infrastructure\Persistence\SqliteAdvertisementRepository;
use Demo\App\Advertisements\Advertisement\UI\Http\ApproveAdvertisementController;
use Demo\App\Advertisements\Advertisement\UI\Http\DeleteAdvertisementController;
use Demo\App\Advertisements\Advertisement\UI\Http\DisableAdvertisementController;
use Demo\App\Advertisements\Advertisement\UI\Http\EnableAdvertisementController;
use Demo\App\Advertisements\Advertisement\UI\Http\PublishAdvertisementController;
use Demo\App\Advertisements\Advertisement\UI\Http\RenewAdvertisementController;
use Demo\App\Advertisements\Advertisement\UI\Http\UpdateAdvertisementController;
use Demo\App\Advertisements\User\Application\Command\DisableMember\DisableMemberUseCase;
use Demo\App\Advertisements\User\Application\Command\EnableMember\EnableMemberUseCase;
use Demo\App\Advertisements\User\Application\Command\SignUpMember\SignUpMemberUseCase;
use Demo\App\Advertisements\User\Domain\UserRepository;
use Demo\App\Advertisements\User\Infrastructure\Persistence\SqliteUserRepository;
use Demo\App\Advertisements\User\UI\Http\DisableMemberController;
use Demo\App\Advertisements\User\UI\Http\EnableMemberController;
use Demo\App\Advertisements\User\UI\Http\SignUpMemberController;
use Demo\App\Framework\Database\DatabaseConnection;
use Demo\App\Framework\Database\SqliteConnection;
use Demo\App\Framework\Database\SqliteTransactionManager;
use Demo\App\Framework\Database\TransactionManager;
use Demo\App\Framework\SecurityUser\FrameworkSecurityService;
use Demo\App\Framework\SecurityUser\SecurityUserRepository;
use Demo\App\Framework\SecurityUser\SqliteSecurityUserRepository;

class DependencyInjectionResolver
{
    private ?DatabaseConnection $connection = null;

    public function publishAdvertisementController(): PublishAdvertisementController
    {
        return new PublishAdvertisementController($this->publishAdvertisementUseCase(), $this->frameworkSecurityService());
    }

    public function updateAdvertisementController(): UpdateAdvertisementController
    {
        return new UpdateAdvertisementController($this->updateAdvertisementUseCase(), $this->frameworkSecurityService());
    }

    public function renewAdvertisementController(): RenewAdvertisementController
    {
        return new RenewAdvertisementController($this->renewAdvertisementUsecase());
    }

    public function disableAdvertisementController(): DisableAdvertisementController
    {
        return new DisableAdvertisementController($this->disableAdvertisementUseCase(), $this->frameworkSecurityService());
    }

    public function disableAdvertisementUseCase(): DisableAdvertisementUseCase
    {
        return new DisableAdvertisementUseCase($this->advertisementRepository(), $this->securityService(), $this->transactionManager());
    }

    public function deleteAdvertisementUseCase(): DeleteAdvertisementUseCase
    {
        return new DeleteAdvertisementUseCase($this->advertisementRepository(), $this->securityService(), $this->transactionManager());
    }

    public function deleteAdvertisementController(): DeleteAdvertisementController
    {
        return new DeleteAdvertisementController($this->deleteAdvertisementUseCase(), $this->frameworkSecurityService());
    }

    public function securityService(): AdvertisementSecurityService
    {
        return new AdvertisementSecurityService($this->userRepository());
    }

    public function approveAdvertisementController(): ApproveAdvertisementController
    {
        return new ApproveAdvertisementController($this->approveAdvertisementUseCase(), $this->frameworkSecurityService());
    }

    public function approveAdvertisementUseCase(): ApproveAdvertisementUseCase
    {
        return new ApproveAdvertisementUseCase($this->advertisementRepository(), $this->securityService(), $this->transactionManager());
    }

    public function enableAdvertisementController(): EnableAdvertisementController
    {
        return new EnableAdvertisementController($this->enableAdvertisementUseCase(), $this->frameworkSecurityService(), $this->securityService());
    }

    public function enableAdvertisementUseCase(): EnableAdvertisementUseCase
    {
        return new EnableAdvertisementUseCase($this->advertisementRepository(), $this->userRepository(), $this->securityService(), $this->transactionManager());
    }

    public function publishAdvertisementUseCase(): PublishAdvertisementUseCase
    {
        return new PublishAdvertisementUseCase($this->advertisementRepository(), $this->userRepository(), $this->transactionManager());
    }

     public function renewAdvertisementUseCase(): RenewAdvertisementUseCase
     {
         return new RenewAdvertisementUseCase($this->advertisementRepository());
     }

    public function updateAdvertisementUseCase(): UpdateAdvertisementUseCase
    {
        return new UpdateAdvertisementUseCase($this->advertisementRepository(), $this->securityService(), $this->transactionManager());
    }

    public function advertisementRepository(): AdvertisementRepository
    {
        return new SqliteAdvertisementRepository($this->connection());
    }

    public function signUpMemberController(): SignUpMemberController
    {
        return new SignUpMemberController($this->signUpMemberUseCase(), $this->frameworkSecurityService());
    }

    public function disableMemberController(): DisableMemberController
    {
        return new DisableMemberController($this->disableMemberUseCase(), $this->frameworkSecurityService());
    }

    public function disableMemberUseCase(): DisableMemberUseCase
    {
        return new DisableMemberUseCase($this->userRepository());
    }

    public function enableMemberController(): EnableMemberController
    {
        return new EnableMemberController($this->enableMemberUseCase(), $this->frameworkSecurityService());
    }

    public function enableMemberUseCase(): EnableMemberUseCase
    {
        return new EnableMemberUseCase($this->userRepository());
    }

    public function frameworkSecurityService(): FrameworkSecurityService
    {
        return new FrameworkSecurityService($this->securityUserRepository());
    }

    public function securityUserRepository(): SecurityUserRepository
    {
        return new SqliteSecurityUserRepository($this->connection());
    }

    public function signUpMemberUseCase(): SignUpMemberUseCase
    {
        return new SignUpMemberUseCase($this->userRepository());
    }

    public function userRepository(): UserRepository
    {
        return new SqliteUserRepository($this->connection());
    }

    public function connection(): DatabaseConnection
    {
        if ($this->connection === null) {
            $this->connection = new SqliteConnection();
        }
        return $this->connection;
    }

    public function transactionManager(): TransactionManager
    {
        return new SqliteTransactionManager($this->connection());
    }
}
