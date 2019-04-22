<?php

namespace Robier\SiteMaps\Middleware;

use Iterator;
use Robier\SiteMaps\Contract;

class Min implements Contract\Middleware
{
    protected $min;

    public function __construct(int $min)
    {
        $this->min = $min;
    }

    public function apply(Iterator $iterator): Iterator
    {
        $count = 1;
        $savedItems = [];

        foreach ($iterator as $item) {
            if ($count < $this->min) {
                $savedItems[] = $item;
                ++$count;
                continue;
            }

            if (!empty($savedItems)) {
                yield from $savedItems;
                $savedItems = []; // free memory
            }

            yield $item;
        }
    }
}
