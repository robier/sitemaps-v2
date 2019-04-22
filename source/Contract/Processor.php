<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Contract;

use Generator;
use Iterator;

interface Processor
{
    public function apply(Iterator $items): Generator;
}
