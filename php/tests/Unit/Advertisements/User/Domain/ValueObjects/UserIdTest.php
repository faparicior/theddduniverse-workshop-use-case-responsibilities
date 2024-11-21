<?php
declare(strict_types=1);

namespace Tests\Demo\App\Unit\Advertisements\User\Domain\ValueObjects;

use Demo\App\Advertisements\Shared\Exceptions\InvalidUniqueIdentifierException;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

class UserIdTest extends TestCase
{
    private const string ID = '6fa00b21-2930-483e-b610-d6b0e5b19b29';
    private const string INVALID_ID = '6fa00b21-2930-983e-b610-d6b0e5b19b29';

    public function testShouldCreateAnUserId()
    {
        $userId = new UserId(self::ID);
        $this->assertEquals(self::ID, $userId->value());
    }

    public function testShouldThrowAnExceptionWhenIdHasNotUuidV4Standards()
    {
        $this->expectException(InvalidUniqueIdentifierException::class);
        $this->expectExceptionMessage('Invalid unique identifier format for ' . self::INVALID_ID);
        new UserId(self::INVALID_ID);
    }

    public function testShouldBeAbleToCompare()
    {
        $userId = new UserId(self::ID);
        $userId2 = new UserId(self::ID);
        $this->assertTrue($userId->equals($userId2));
    }
}
