<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model;

use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementDate;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use Demo\App\Advertisement\Domain\Model\ValueObject\Description;
use Demo\App\Advertisement\Domain\Model\ValueObject\Email;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;

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
