<?php
declare(strict_types=1);

namespace Tests\Demo\App\Unit\Advertisements\CivicCenter\ValueObjects;

use Demo\App\Advertisements\Advertisement\Domain\Exceptions\DescriptionEmptyException;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\DescriptionTooLongException;
use Demo\App\Advertisements\CivicCenter\Domain\ValueObjects\CivicCenterName;
use PHPUnit\Framework\TestCase;

class CivicCenterNameTest extends TestCase
{
    private const string DESCRIPTION = 'description';

    public function testShouldCreateACivicCenterName()
    {
        $civicCenterName = new CivicCenterName(self::DESCRIPTION);
        $this->assertEquals(self::DESCRIPTION, $civicCenterName->value());
    }

    public function testShouldThrowAnExceptionWhenCivicCenterNameHasMoreThan200Characters()
    {
        $this->expectException(DescriptionTooLongException::class);
        $this->expectExceptionMessage('Description has more than 200 characters: Has 201 characters');
        $randomString = str_repeat('a', 201);
        new CivicCenterName($randomString);
    }

    public function testShouldThrowAnExceptionWhenCivicCenterNameIsEmpty()
    {
        $this->expectException(DescriptionEmptyException::class);
        $this->expectExceptionMessage('Description empty');
        new CivicCenterName('');
    }
}
