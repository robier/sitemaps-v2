<?php

declare(strict_types=1);

namespace Robier\SiteMaps;

use DateTimeInterface;
use InvalidArgumentException;
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
        if(!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED|FILTER_FLAG_HOST_REQUIRED)){
            throw new InvalidArgumentException(sprintf('URL %s is not RFC compliant', $url));
        }

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
