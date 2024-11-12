<?php
declare(strict_types=1);

namespace Demo\App\Framework;

use Demo\App\Advertisements\Advertisement\Application\Command\PublishAdvertisement\PublishAdvertisementUseCase;
use Demo\App\Advertisements\Advertisement\Application\Command\RenewAdvertisement\RenewAdvertisementUseCase;
use Demo\App\Advertisements\Advertisement\Application\Command\UpdateAdvertisement\UpdateAdvertisementUseCase;
use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Infrastructure\Persistence\SqliteAdvertisementRepository;
use Demo\App\Advertisements\Advertisement\UI\Http\PublishAdvertisementController;
use Demo\App\Advertisements\Advertisement\UI\Http\RenewAdvertisementController;
use Demo\App\Advertisements\Advertisement\UI\Http\UpdateAdvertisementController;
use Demo\App\Advertisements\User\Application\Command\SignUpMember\SignUpMemberUseCase;
use Demo\App\Advertisements\User\Domain\UserRepository;
use Demo\App\Advertisements\User\Infrastructure\Persistence\SqliteUserRepository;
use Demo\App\Advertisements\User\UI\Http\SignUpMemberController;
use Demo\App\Framework\Database\DatabaseConnection;
use Demo\App\Framework\Database\SqliteConnection;

class DependencyInjectionResolver
{
    public function publishAdvertisementController(): PublishAdvertisementController
    {
        return new PublishAdvertisementController($this->publishAdvertisementUseCase());
    }

    public function updateAdvertisementController(): UpdateAdvertisementController
    {
        return new UpdateAdvertisementController($this->updateAdvertisementUseCase());
    }

    public function renewAdvertisementController(): RenewAdvertisementController
    {
        return new RenewAdvertisementController($this->renewAdvertisementUsecase());
    }

    public function publishAdvertisementUseCase(): PublishAdvertisementUseCase
    {
        return new PublishAdvertisementUseCase($this->advertisementRepository());
    }

     public function renewAdvertisementUseCase(): RenewAdvertisementUseCase
     {
         return new RenewAdvertisementUseCase($this->advertisementRepository());
     }

    public function updateAdvertisementUseCase(): UpdateAdvertisementUseCase
    {
        return new UpdateAdvertisementUseCase($this->advertisementRepository());
    }

    public function advertisementRepository(): AdvertisementRepository
    {
        return new SqliteAdvertisementRepository(self::connection());
    }

    public function signUpMemberController(): SignUpMemberController
    {
        return new SignUpMemberController($this->signUpMemberUseCase());
    }

    public function signUpMemberUseCase(): SignUpMemberUseCase
    {
        return new SignUpMemberUseCase($this->userRepository());
    }

    public function userRepository(): UserRepository
    {
        return new SqliteUserRepository(self::connection());
    }

    public function connection(): DatabaseConnection
    {
        return new SqliteConnection();
    }
}
