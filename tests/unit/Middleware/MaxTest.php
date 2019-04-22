<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Test\Unit\Middleware;

use Robier\SiteMaps\Middleware\Max;
use PHPUnit\Framework\TestCase;

class MaxTest extends TestCase
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
    public function testMaxIterations(int $maxItems, int $itemCount)
    {
        $max = new Max($maxItems);

        $generator = $max->apply($this->fakeIterator($itemCount));

        if($itemCount <= $maxItems){
            $this->assertCount($itemCount, $generator);
        }else{
            $this->assertCount($maxItems, $generator);
        }
    }
}
