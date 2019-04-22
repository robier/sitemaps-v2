<?php

namespace Robier\SiteMaps\Processor\Apply;

use Generator;
use Iterator;
use Robier\SiteMaps\Contract;

class Wildcard implements Contract\Processor
{
    protected $handler;

    public function __construct(string $group, Contract\Processor $processor, Contract\Processor ...$processors)
    {
        $group = str_replace('*', '.{1,}', $group);

        $regex = sprintf('/%s/iU', $group);

        $this->handler = new Regex($regex, $processor, ...$processors);
    }


    public function apply(Iterator $items): Generator
    {
        return $this->handler->apply($items);
    }
}
