<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Test\Unit;

use DateTime;
use DateTimeInterface;
use InvalidArgumentException;
use Robier\SiteMaps\Location;
use PHPUnit\Framework\TestCase;
use Robier\SiteMaps\Location\ChangeFrequency;
use Robier\SiteMaps\Location\Priority;

class LocationTest extends TestCase
{
    public function dataProviderForGetters()
    {
        yield ['http://example.com', null, null, null];
        yield ['http://example.com/test', null, null, null];
        yield ['http://example.com/test/1', $this->stub(Priority::class), null, null];
        yield ['http://example.com/test/2', $this->stub(Priority::class), $this->stub(ChangeFrequency::class), null];
        yield ['http://google.com', $this->stub(Priority::class), $this->stub(ChangeFrequency::class), $this->stub(DateTime::class)];
        yield ['http://bing.com', $this->stub(Priority::class), null, $this->stub(DateTime::class)];
        yield ['http://test.com', null, null, $this->stub(DateTime::class)];
    }

    public function dataProviderForBadLink()
    {
        yield ['test'];
        yield ['example.com'];
        yield ['example.com/test'];
        yield ['example.com test'];
    }

    protected function stub(string $className)
    {
        return $this->createMock($className);
    }


    /**
     * @dataProvider dataProviderForGetters
     *
     * @param string $link
     * @param null|Priority $priority
     * @param null|ChangeFrequency $changeFrequency
     * @param DateTimeInterface|null $lastModified
     */
    public function testGetters(string $link, ?Priority $priority = null, ?ChangeFrequency $changeFrequency = null, ?DateTimeInterface $lastModified = null)
    {
        $location = new Location($link, $priority, $changeFrequency, $lastModified);

        $this->assertEquals($link, $location->url());
        $this->assertEquals($priority, $location->priority());
        $this->assertEquals($changeFrequency, $location->changeFrequency());
        $this->assertEquals($lastModified, $location->lastModified());
    }

    /**
     * @dataProvider dataProviderForBadLink
     *
     * @param string $link
     */
    public function testBadLinkProvided(string $link)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('URL %s is not RFC compliant', $link));

        new Location($link);
    }
}
