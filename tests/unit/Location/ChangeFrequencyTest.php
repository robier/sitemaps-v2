<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Test\Unit\Location;

use InvalidArgumentException;
use Robier\SiteMaps\Location\ChangeFrequency;
use PHPUnit\Framework\TestCase;

class ChangeFrequencyTest extends TestCase
{
    public function dataProviderValidTypes()
    {
        yield ['always'];
        yield ['AlWaYs'];
        yield ['hourly'];
        yield ['hOuRly'];
        yield ['daily'];
        yield ['weekly'];
        yield ['wEEkLy'];
        yield ['monthly'];
        yield ['yEARly'];
        yield ['never'];
        yield ['NEVER'];
    }

    public function dataProviderInvalidTypes()
    {
        yield ['123'];
        yield ['1.3'];
        yield ['test'];
        yield ['bad value'];
    }

    /**
     * @dataProvider dataProviderValidTypes
     */
    public function testSuccessfullyConstructingObject(string $type): void
    {
        $changeFrequency = new ChangeFrequency($type);

        $lowerCaseType = strtolower($type);

        $this->assertEquals($lowerCaseType, $changeFrequency->value());
        $this->assertEquals($lowerCaseType, (string)$changeFrequency);
        $this->assertEquals($lowerCaseType, $changeFrequency->__toString());
    }

    /**
     * @dataProvider dataProviderValidTypes
     */
    public function testFactoryMethods(string $type): void
    {
        /** @var ChangeFrequency $changeFrequency */
        $changeFrequency = ChangeFrequency::{$type}();

        $lowerCaseType = strtolower($type);

        $this->assertEquals($lowerCaseType, $changeFrequency->value());
        $this->assertEquals($lowerCaseType, (string)$changeFrequency);
        $this->assertEquals($lowerCaseType, $changeFrequency->__toString());
    }


    /**
     * @dataProvider dataProviderInvalidTypes
     */
    public function testValidationFail(string $badType): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Invalid change frequency type %s', $badType));

        new ChangeFrequency($badType);
    }

    public function testAllMethod()
    {
        $values = ChangeFrequency::all();

        $this->assertTrue(is_array($values));

        $possibleValues = ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'];

        foreach($possibleValues as $key => $possibility){
            if(in_array($possibility, $values)){
                unset($possibleValues[$key]);
            }
        }

        $this->assertCount(0, $possibleValues);
    }
}
