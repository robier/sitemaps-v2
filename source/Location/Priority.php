<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Location;

use InvalidArgumentException;

class Priority
{
    protected $value;

    public function __construct(float $value)
    {
        if ($value < 0 || $value > 1) {
            throw new InvalidArgumentException(sprintf('Priority must be a number between 0 and 1, %f provided', $value));
        }

        $this->value = $value;
    }

    public function value(): float
    {
        return $this->value;
    }
}
