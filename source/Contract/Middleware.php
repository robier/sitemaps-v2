<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Contract;

use Iterator;

interface Middleware
{
    public function apply(Iterator $iterator): Iterator;
}
