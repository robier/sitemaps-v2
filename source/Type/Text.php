<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Type;

use Iterator;
use Robier\SiteMaps\Location;

class Text extends Base
{
    protected const MAX_ITEMS     = 50000;
    protected const MAX_FILE_SIZE = 10485760; // ~10 MB
    protected const EXTENSION     = 'txt';

    protected function generateFile(string $fullPath, string $group, Iterator $chunk): int
    {
        $handle = fopen($fullPath, 'w');

        /** @var Location $item */
        $count = 0;
        foreach ($chunk as $item) {
            fwrite($handle, $item->url() . PHP_EOL);
            ++$count;
        }

        fclose($handle);

        return $count;
    }
}
