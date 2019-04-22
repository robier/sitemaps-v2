<?php

namespace Robier\SiteMaps\Processor\IndexFile;

use Generator;
use Iterator;
use Robier\SiteMaps\Contract\Processor;
use Robier\SiteMaps\File\SiteMap;
use Robier\SiteMaps\Path;
use Robier\SiteMaps\Processor\IndexFile;

class PerGroup implements Processor
{
    protected $path;
    protected $dateFormat;
    protected $indent;


    public function __construct(Path $path, string $dateFormat = 'Y-m-d', bool $indent = true)
    {
        $this->path = $path;
        $this->dateFormat = $dateFormat;
        $this->indent = $indent;
    }


    public function apply(Iterator $items): Generator
    {
        $groups = [];

        foreach ($items as $item) {

            if (!$item instanceof SiteMap) {
                yield $item;
                continue;
            }

            if (!array_key_exists($item->group(), $groups)) {
                $groups[$item->group()] = [];
            }

            $groups[$item->group()][] = $item;
        }

        foreach ($groups as $name => $group) {
            $indexFile = new IndexFile($this->path, $name, $this->dateFormat, $this->indent);

            yield from $indexFile->apply($this->yield($group));
        }
    }

    protected function yield(array $items): Generator
    {
        yield from $items;
    }
}
