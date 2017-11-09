<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Processor;

use Generator;
use Iterator;
use Robier\SiteMaps\Contract;

class BlackHole implements Contract\Processor
{
    public function apply(Iterator $items, string $group): Generator
    {
        /** @var Contract\File $item */
        foreach($items as $item){
            $fullPath = $item->fullPath();
            if(is_readable($fullPath)){
                unlink($fullPath);
            }
        }

        yield from []; // empty generator as files does not exist any more
    }
}
