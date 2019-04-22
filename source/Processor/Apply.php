<?php

namespace Robier\SiteMaps\Processor;

use Generator;
use Iterator;
use Robier\SiteMaps\Common\MultiProcessorTrait;
use Robier\SiteMaps\Contract;

abstract class Apply implements Contract\Processor
{
    use MultiProcessorTrait;

    public function apply(Iterator $items): Generator
    {
        $group = [];

        foreach($items as $item){

            if(!$this->validate($item)){
                yield $item;
                continue;
            }

            $group[] = $item;
        }

        $generator = $this->yield($group);

        foreach($this->processors as $processor){
            $generator = $processor->apply($generator);
        }

        yield from $generator;
    }

    protected function yield(array $items): Generator
    {
        yield from $items;
    }

    protected abstract function validate(Contract\File $file): bool;
}
