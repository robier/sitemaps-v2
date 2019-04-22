<?php

namespace Robier\SiteMaps\Processor\IndexFile;

use Generator;
use Iterator;
use Robier\SiteMaps\Contract\Processor;
use Robier\SiteMaps\Iterator\GroupFiles;
use Robier\SiteMaps\Path;
use Robier\SiteMaps\Processor\IndexFile;

class GroupTest implements Processor
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
        $groups = new GroupFiles($items);

        foreach ($groups as $key => $group) {

            if (is_null($key)) {
                yield from $group;
                continue;
            }

            $indexFile = new IndexFile($this->path, $key, $this->dateFormat, $this->indent);
            yield from $indexFile->apply($group);
        }
    }
}
