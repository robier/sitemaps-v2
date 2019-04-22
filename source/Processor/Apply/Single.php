<?php

namespace Robier\SiteMaps\Processor\Apply;

use Robier\SiteMaps\Contract;
use Robier\SiteMaps\File\SiteMap;
use Robier\SiteMaps\Processor\Apply;

class Single extends Apply
{
    protected $group;

    public function __construct(string $groups, Contract\Processor $processor, Contract\Processor ...$processors)
    {
        $this->group = $groups;
        parent::__construct($processor, ...$processors);
    }

    protected function validate(Contract\File $file): bool
    {
        if(!$file instanceof SiteMap){
            return false;
        }

        return $this->group === $file->group();
    }
}
