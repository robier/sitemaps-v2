<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Common;

use Robier\SiteMaps\Path;

trait FileTrait
{
    protected $path;
    protected $name;

    protected $linksCount;

    public function __construct(int $linksCount, Path $path, string $name)
    {
        $this->linksCount = $linksCount;
        $this->path = $path;
        $this->name = $name;
    }

    public function fullPath(): string
    {
        return $this->path() . $this->name();
    }

    public function path(): string
    {
        return $this->path->path();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function fullUrl(): string
    {
        return $this->url() . $this->name();
    }

    public function url(): string
    {
        return $this->path->url();
    }

    public function count(): int
    {
        return $this->linksCount;
    }
}
