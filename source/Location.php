<?php

declare(strict_types=1);

namespace Robier\SiteMaps;

use DateTimeInterface;
use Robier\SiteMaps\Location\ChangeFrequency;
use Robier\SiteMaps\Location\Priority;

class Location
{
    protected $url;
    protected $priority;
    protected $changeFrequency;
    protected $lastModified;

    /**
     * Item constructor.
     *
     * @param string $url
     * @param Priority $priority
     * @param ChangeFrequency $changeFrequency
     * @param DateTimeInterface|null $lastModified
     */
    public function __construct(string $url, ?Priority $priority = null, ?ChangeFrequency $changeFrequency = null, ?DateTimeInterface $lastModified = null)
    {
        $this->url = $url;
        $this->priority = $priority;
        $this->changeFrequency = $changeFrequency;
        $this->lastModified = $lastModified;
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * @return null|Priority
     */
    public function priority(): ?Priority
    {
        return $this->priority;
    }

    /**
     * @return null|ChangeFrequency
     */
    public function changeFrequency(): ?ChangeFrequency
    {
        return $this->changeFrequency;
    }

    /**
     * @return null|DateTimeInterface
     */
    public function lastModified(): ?DateTimeInterface
    {
        return $this->lastModified;
    }
}
