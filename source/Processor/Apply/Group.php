<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Processor\Apply;

use Generator;
use Iterator;
use Robier\SiteMaps\Contract;

class Group implements Contract\Processor
{
    protected $handler;

    public function __construct(string $group, Contract\Processor $processor, Contract\Processor ...$processors)
    {
        $this->handler = new Groups([$group], $processor, ...$processors);
    }

    public function apply(Iterator $items, string $group): Generator
    {
        yield from $this->handler->apply($items, $group);
    }
}
