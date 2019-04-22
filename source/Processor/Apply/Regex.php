<?php

namespace Robier\SiteMaps\Processor\Apply;

use Robier\SiteMaps\Contract;
use Robier\SiteMaps\File\SiteMap;
use Robier\SiteMaps\Processor\Apply;

class Regex extends Apply
{
    protected $pattern;

    public function __construct(string $pattern, Contract\Processor $processor, Contract\Processor ...$processors)
    {
        $this->pattern = $pattern;
        parent::__construct($processor, ...$processors);
    }

    protected function validate(Contract\File $file): bool
    {
        if(!$file instanceof SiteMap){
            return false;
        }

        return preg_match($this->pattern, $file->group());
    }
}
