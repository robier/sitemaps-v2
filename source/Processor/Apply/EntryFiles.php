<?php

namespace Robier\SiteMaps\Processor\Apply;

use Robier\SiteMaps\Contract;
use Robier\SiteMaps\File\SiteMap;
use Robier\SiteMaps\File\SiteMapIndex;
use Robier\SiteMaps\Processor\Apply;

class EntryFiles extends Apply
{
    protected function validate(Contract\File $file): bool
    {
        if ($file instanceof SiteMapIndex) {
            return true;
        }

        if ($file instanceof SiteMap && !$file->index()) {
            return true;
        }

        return false;
    }
}
