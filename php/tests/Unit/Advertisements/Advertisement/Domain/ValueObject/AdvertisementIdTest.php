<?php
declare(strict_types=1);

namespace Tests\Demo\App\Unit\Advertisements\Advertisement\Domain\ValueObject;

use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Shared\Exceptions\InvalidUniqueIdentifierException;
use PHPUnit\Framework\TestCase;

class AdvertisementIdTest extends TestCase
{
    private const string ID = '6fa00b21-2930-483e-b610-d6b0e5b19b29';
    private const string INVALID_ID = '6fa00b21-2930-983e-b610-d6b0e5b19b29';

    public function testShouldCreateAnAdvertisementId()
    {
        $advertisementId = new AdvertisementId(self::ID);
        $this->assertEquals(self::ID, $advertisementId->value());
    }

    public function testShouldThrowAnExceptionWhenIdHasNotUuidV4Standards()
    {
        $this->expectException(InvalidUniqueIdentifierException::class);
        $this->expectExceptionMessage('Invalid unique identifier format for ' . self::INVALID_ID);
        new AdvertisementId(self::INVALID_ID);
    }
}
