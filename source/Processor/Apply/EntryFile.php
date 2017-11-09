<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Processor\Apply;

use Generator;
use Iterator;
use Robier\SiteMaps\Contract;
use Robier\SiteMaps\Processor\Apply;

class EntryFile implements Contract\Processor
{
    protected $handler;

    public function __construct(Contract\Processor $processor, Contract\Processor ...$processors)
    {
        $this->handler = new Apply(
            function(Contract\File $item): bool{
                return $item->hasSiteMapIndex() === false;
            },
            $processor,
            ...$processors
        );
    }

    public function apply(Iterator $items, string $group): Generator
    {
        yield from $this->handler->apply($items, $group);
    }
}
