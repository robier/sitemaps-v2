<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Processor\Apply;

use Generator;
use Iterator;
use Robier\SiteMaps\Contract;
use Robier\SiteMaps\Processor\Apply;

class Groups implements Contract\Processor
{
    protected $handler;

    public function __construct(array $groups, Contract\Processor $processor, Contract\Processor ...$processors)
    {
        $this->handler = new Apply(
            function(Contract\File $file, string $currentGroup) use ($groups): bool {
                return in_array($currentGroup, $groups);
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
