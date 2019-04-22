<?php

declare(strict_types=1);

namespace Robier\SiteMaps\File;

use DateTimeInterface;
use Robier\SiteMaps\Contract;
use Robier\SiteMaps\Common;
use Robier\SiteMaps\Path;

class SiteMap implements Contract\File
{
    use Common\FileTrait {
        __construct as protected traitConstructor;
    }

    protected $group;
    protected $lastModified;
    protected $siteMapIndex;

    /**
     * Item constructor.
     *
     * @param int $linksCount
     * @param string $group
     * @param Path $path
     * @param string $name
     * @param string|DateTimeInterface $lastModified
     */
    public function __construct(int $linksCount, string $group, Path $path, string $name, ?DateTimeInterface $lastModified = null)
    {
        $this->traitConstructor($linksCount, $path, $name);

        $this->group = $group;
        $this->lastModified = $lastModified;
    }

    /**
     * @return null|DateTimeInterface
     */
    public function lastModified(): ?DateTimeInterface
    {
        return $this->lastModified;
    }

    public function group(): string
    {
        return $this->group;
    }

    public function changeName(string $name): Contract\File
    {
        $this->name = $name;

        return $this;
    }

    public function changeIndex(SiteMapIndex $siteMapIndex): Contract\File
    {
        $this->siteMapIndex = $siteMapIndex;

        return $this;
    }

    public function index(): ?SiteMapIndex
    {
        return $this->siteMapIndex;
    }
}
