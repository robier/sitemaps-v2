<?php

namespace Robier\SiteMaps\Test\Unit\File;

use Generator;
use PHPUnit\Framework\TestCase;
use Robier\SiteMaps\File\SiteMapIndex;
use Robier\SiteMaps\Path;

class SiteMapIndexTest extends TestCase
{
    public function dataProviderValid(): Generator
    {
        yield [0, $this->mockPath(), 'name'];
    }

    public function dataProviderForChangingName(): Generator
    {
        yield ['old name', 'new name'];
    }

    public function dataProviderForChangingCount(): Generator
    {
        yield [0, 15];
        yield [10, 5];
        yield [10, 0];
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
    public function testGetters(int $count, Path $path, string $name): void
    {
        $siteMapIndex = new SiteMapIndex($count, $path, $name);

        $this->assertEquals($count, $siteMapIndex->count());
        $this->assertEquals($name, $siteMapIndex->name());
        $this->assertEquals($path->path(), $siteMapIndex->path());
        $this->assertEquals($path->url(), $siteMapIndex->url());
        $this->assertEquals($path->path() . $name, $siteMapIndex->fullPath());
        $this->assertEquals($path->url() . $name, $siteMapIndex->fullUrl());
    }

    /**
     * @dataProvider dataProviderForChangingName
     */
    public function testChangingName(string $oldName, string $newName): void
    {
        $path = $this->mockPath();

        $siteMapIndex = new SiteMapIndex(0, $path, $oldName);

        $this->assertEquals($oldName, $siteMapIndex->name());
        $this->assertEquals($path->path() . $oldName, $siteMapIndex->fullPath());
        $this->assertEquals($path->url() . $oldName, $siteMapIndex->fullUrl());

        $siteMapIndex->changeName($newName);

        $this->assertEquals($newName, $siteMapIndex->name());
        $this->assertEquals($path->path() . $newName, $siteMapIndex->fullPath());
        $this->assertEquals($path->url() . $newName, $siteMapIndex->fullUrl());
    }

    /**
     * @dataProvider dataProviderForChangingCount
     */
    public function testChangingCount(int $oldCount, int $newCount): void
    {
        $siteMapIndex = new SiteMapIndex($oldCount, $this->mockPath(), 'test');

        $this->assertEquals($oldCount, $siteMapIndex->count());

        $siteMapIndex->changeCount($newCount);

        $this->assertEquals($newCount, $siteMapIndex->count());
    }
}
