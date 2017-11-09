<?php

namespace Robier\SiteMaps\Middleware;

use Iterator;
use Robier\SiteMaps\Contract;

class Max implements Contract\Middleware
{
    protected $max;

    public function __construct(int $max)
    {
        $this->max = $max;
    }

    public function apply(Iterator $iterator, string $group): Iterator
    {
        $count = 0;
        foreach($iterator as $item){

            yield $item;

            ++$count;

            if ($count >= $this->max) {
                break;
            }
        }
    }
}
