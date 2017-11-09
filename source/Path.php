<?php

declare(strict_types=1);

namespace Robier\SiteMaps;

class Path
{
    protected $path;
    protected $url;

    public function __construct(string $path, string $url)
    {
        $this->path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->url = rtrim($url, '/') . '/';

        if (!is_dir($this->path)) {
            throw new \InvalidArgumentException('Path must point to existing directory');
        }
    }

    public function path(): string
    {
        return $this->path;
    }

    public function url(): string
    {
        return $this->url;
    }
}
