<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Test\Unit\Middleware;

use Robier\SiteMaps\Middleware\Min;
use PHPUnit\Framework\TestCase;

class MinTest extends TestCase
{
    protected function fakeIterator(int $count)
    {
        for($i = 0; $i < $count; $i++){
            yield $i;
        }
    }

    public function dataProvider()
    {
        yield [1, 5];
        yield [5, 4];
        yield [5, 5];
        yield [5, 6];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testMaxIterations(int $minItems, int $itemCount)
    {
        $max = new Min($minItems);

        $generator = $max->apply($this->fakeIterator($itemCount));

        if($minItems > $itemCount){
            $this->assertCount(0, $generator);
        }else{
            $this->assertCount($itemCount, $generator);
        }
    }
}
