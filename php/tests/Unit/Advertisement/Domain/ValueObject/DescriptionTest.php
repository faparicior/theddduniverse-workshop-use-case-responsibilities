<?php
declare(strict_types=1);

namespace Tests\Demo\App\Unit\Advertisement\Domain\ValueObject;

use Demo\App\Advertisement\Domain\Exceptions\DescriptionEmptyException;
use Demo\App\Advertisement\Domain\Exceptions\DescriptionTooLongException;
use Demo\App\Advertisement\Domain\Model\ValueObject\Description;
use PHPUnit\Framework\TestCase;

class DescriptionTest extends TestCase
{
    private const string DESCRIPTION = 'description';

    public function testShouldCreateADescription()
    {
        $advertisementId = new Description(self::DESCRIPTION);
        $this->assertEquals(self::DESCRIPTION, $advertisementId->value());
    }

    public function testShouldThrowAnExceptionWhenDescriptionHasMoreThan200Characters()
    {
        $this->expectException(DescriptionTooLongException::class);
        $this->expectExceptionMessage('Description has more than 200 characters: Has 201 characters');
        $randomString = str_repeat('a', 201);
        new Description($randomString);
    }

    public function testShouldThrowAnExceptionWhenDescriptionIsEmpty()
    {
        $this->expectException(DescriptionEmptyException::class);
        $this->expectExceptionMessage('Description empty');
        new Description('');
    }
}
