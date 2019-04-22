<?php

namespace Robier\SiteMaps\Processor\Apply;

use Robier\SiteMaps\Contract;
use Robier\SiteMaps\File\SiteMap;
use Robier\SiteMaps\Processor\Apply;

class Multiple extends Apply
{
    protected $groups;

    public function __construct(array $groups, Contract\Processor $processor, Contract\Processor ...$processors)
    {
        $this->groups = $groups;
        parent::__construct($processor, ...$processors);
    }

    protected function validate(Contract\File $file): bool
    {
        if (!$file instanceof SiteMap) {
            return false;
        }

        return in_array($file->group(), $this->groups);
    }
}
