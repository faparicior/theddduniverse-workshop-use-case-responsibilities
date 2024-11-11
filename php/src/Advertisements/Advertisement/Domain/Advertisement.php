<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Domain;

use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementDate;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\Description;
use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;

final class Advertisement
{
    public function __construct(
        private readonly AdvertisementId $id,
        private Description $description,
        private Email $email,
        private Password $password,
        private AdvertisementDate $date
    ){
    }

    public function renew(Password $password): void
    {
        $this->password = $password;
        $this->updateDate();
    }

    public function update(Description $description, Email $email, Password $password): void
    {
        $this->description = $description;
        $this->email = $email;
        $this->password = $password;
        $this->updateDate();
    }

    public function id(): AdvertisementId
    {
        return $this->id;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function date(): AdvertisementDate
    {
        return $this->date;
    }

    private function updateDate(): void
    {
        $this->date = new AdvertisementDate(new \DateTime());
    }
}
