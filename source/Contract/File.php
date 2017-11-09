<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Contract;

use Countable;

interface File extends Countable
{
    public function name(): string;

    public function path(): string;

    public function fullPath(): string;

    public function url(): string;

    public function fullUrl(): string;

    public function hasSiteMapIndex(): bool;

    public function changeName(string $name): self;
}
