<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Test\Unit\File;


use DateTime;
use Generator;
use PHPUnit\Framework\TestCase;
use Robier\SiteMaps\File\SiteMap;
use Robier\SiteMaps\File\SiteMapIndex;
use Robier\SiteMaps\Path;

class SiteMapTest extends TestCase
{
    public function dataProviderValid(): Generator
    {
        $dateTime = $this->createMock(DateTime::class);

        yield [0, 'test-group', $this->mockPath(), 'name', $dateTime];
        yield [5, 'test-group2', $this->mockPath(), 'name2', null];
    }

    public function dataProviderForChangingName(): Generator
    {
        yield ['old name', 'new name'];
    }

    public function dataProviderForChangingIndex(): Generator
    {
        $indexFile = $this->createMock(SiteMapIndex::class);

        yield [$indexFile];
    }

    protected function mockPath(string $path = 'test', string $url = 'test'): Path
    {
        $mock = $this->createMock(Path::class);

        $mock->method('path')
            ->willReturn($path);

        $mock->method('url')
            ->willReturn($url);

        return $mock;
    }

    /**
     * @dataProvider dataProviderValid
     */
    public function testGetters(int $count, string $group, Path $path, string $name, ?DateTime $dateTime): void
    {
        $siteMap = new SiteMap($count, $group, $path, $name, $dateTime);

        $this->assertEquals($count, $siteMap->count());
        $this->assertEquals($name, $siteMap->name());
        $this->assertEquals($path->path(), $siteMap->path());
        $this->assertEquals($path->url(), $siteMap->url());
        $this->assertEquals($path->path() . $name, $siteMap->fullPath());
        $this->assertEquals($path->url() . $name, $siteMap->fullUrl());

        $this->assertEquals($group, $siteMap->group());
        $this->assertEquals($dateTime, $siteMap->lastModified());
    }

    /**
     * @dataProvider dataProviderForChangingName
     */
    public function testChangingName(string $oldName, string $newName): void
    {
        $path = $this->mockPath();

        $siteMap = new SiteMap(0, 'test', $path, $oldName);

        $this->assertEquals($oldName, $siteMap->name());
        $this->assertEquals($path->path() . $oldName, $siteMap->fullPath());
        $this->assertEquals($path->url() . $oldName, $siteMap->fullUrl());

        $siteMap->changeName($newName);

        $this->assertEquals($newName, $siteMap->name());
        $this->assertEquals($path->path() . $newName, $siteMap->fullPath());
        $this->assertEquals($path->url() . $newName, $siteMap->fullUrl());
    }

    /**
     * @dataProvider dataProviderForChangingIndex
     */
    public function testChangingSiteMapIndex(SiteMapIndex $index): void
    {
        $path = $this->mockPath();

        $siteMap = new SiteMap(0, 'test', $path, 'name');

        $this->assertNull($siteMap->index());

        $siteMap->changeIndex($index);

        $this->assertSame($index, $siteMap->index());
    }
}
