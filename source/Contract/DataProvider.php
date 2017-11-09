<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Contract;

use Iterator;

interface DataProvider
{
    public function fetch(): Iterator;
}
