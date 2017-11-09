<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Processor;

use Generator;
use Iterator;
use Robier\SiteMaps\Contract;

class Group implements Contract\Processor
{
    protected $processors;

    protected $cache = [];
    protected $groups;

    public function __construct(array $groups, Contract\Processor $processor, Contract\Processor ...$processors)
    {
        $this->groups = $groups;
        array_unshift($processors, $processor);
        $this->processors = $processors;
    }

    public function apply(Iterator $items, string $group): Generator
    {
        $outcome = in_array($group, $this->groups);

        if($outcome === false){
            return $this->yield($items);
        }

        $this->cache[] = $items;


        /** @var Contract\File $item */
        foreach($items as $item){

            $outcome = call_user_func($this->test, $item, $group);

            if($outcome === false){
                yield $item;
                continue;
            }

            /** @var Contract\Processor $processor */
            foreach ($this->processors as $processor){
                yield from $processor->apply($this->yield($item), $group);
            }
        }
    }

    protected function yield(Iterator $items): Generator
    {
        yield from $items;
    }
}
