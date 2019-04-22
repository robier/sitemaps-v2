<?php

namespace Robier\SiteMaps\Common;

use Robier\SiteMaps\Contract;

trait MultiProcessorTrait
{
    protected $processors;

    public function __construct(Contract\Processor $processor, Contract\Processor ...$processors)
    {
        array_unshift($processors, $processor);
        $this->processors = $processors;
    }
}
