<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Domain;

use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementApprovalStatus;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementDate;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementStatus;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\Description;
use Demo\App\Advertisements\Shared\ValueObjects\CivicCenterId;
use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;

final class Advertisement
{
    private AdvertisementStatus $status;
    private AdvertisementApprovalStatus $approvalStatus;

    public function __construct(
        private readonly AdvertisementId $id,
        private Description              $description,
        private Email                    $email,
        private Password                 $password,
        private AdvertisementDate        $date,
        private readonly CivicCenterId   $civicCenterId,
        private readonly UserId          $memberId,
    ){
        $this->status = AdvertisementStatus::ENABLED;
        $this->approvalStatus = AdvertisementApprovalStatus::PENDING_FOR_APPROVAL;
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

    public function status(): AdvertisementStatus
    {
        return $this->status;
    }

    public function approvalStatus(): AdvertisementApprovalStatus
    {
        return $this->approvalStatus;
    }

    public function memberId(): UserId
    {
        return $this->memberId;
    }

    public function civicCenterId(): CivicCenterId
    {
        return $this->civicCenterId;
    }

    public function disable(): void
    {
        $this->status = AdvertisementStatus::DISABLED;
    }

    public function enable(): void
    {
        $this->status = AdvertisementStatus::ENABLED;
    }

    public function approve(): void
    {
        $this->approvalStatus = AdvertisementApprovalStatus::APPROVED;
    }
}
