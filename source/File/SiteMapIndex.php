<?php

declare(strict_types=1);

namespace Robier\SiteMaps\File;

use Robier\SiteMaps\Contract;
use Robier\SiteMaps\Common;

class SiteMapIndex implements Contract\File
{
    use Common\FileTrait;

    public function changeName(string $name): Contract\File
    {
        $this->name = $name;

        return $this;
    }
}
