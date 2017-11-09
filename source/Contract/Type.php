<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Contract;

use Iterator;
use Robier\SiteMaps\Path;

interface Type
{
    public function write(Path $data, string $group, Iterator $links): Iterator;
}
