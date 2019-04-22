<?php

namespace Robier\SiteMaps\Test\Unit\Location;

use Generator;
use InvalidArgumentException;
use Robier\SiteMaps\Location\Priority;
use PHPUnit\Framework\TestCase;

class PriorityTest extends TestCase
{
    public function dataProviderValidData(): Generator
    {
        for($i = 0.0; $i <= 1.0; $i += 0.1){
            yield [$i];
        }
    }

    public function dataProviderInvalidData(): Generator
    {
        yield [-1];
        yield [1.1];
        yield [1.0000001];
        yield [50];
    }

    /**
     * @dataProvider dataProviderValidData
     */
    public function testSuccessfullyConstructingObject(float $value)
    {
        $priority = new Priority($value);

        $this->assertEquals($value, $priority->value());
    }

    /**
     * @dataProvider dataProviderInvalidData
     */
    public function testValidation(float $badValue)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Priority must be a number between 0 and 1, %f provided', $badValue));

        new Priority($badValue);
    }
}
