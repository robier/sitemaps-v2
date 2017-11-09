<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Processor;

use Generator;
use Iterator;
use Robier\SiteMaps\Contract;

class Apply implements Contract\Processor
{
    protected $test;
    protected $processors;

    protected $cache;

    public function __construct(callable $test, Contract\Processor $processor, Contract\Processor ...$processors)
    {
        $this->test = $test;
        array_unshift($processors, $processor);
        $this->processors = $processors;
    }

    public function apply(Iterator $items, string $group): Generator
    {
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

    protected function yield(Contract\File $file): Generator
    {
        yield $file;
    }
}
