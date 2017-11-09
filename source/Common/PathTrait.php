<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Common;

trait PathTrait
{
    protected function path(string $path, string $name, int $index): string
    {
        return sprintf('%s%s-%d.%s', $path, $name, $index, $this->extension());
    }

    protected abstract function extension(): string;
}
