<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Test\Unit;

use InvalidArgumentException;
use Robier\SiteMaps\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    public function dataProviderForGetters()
    {
        yield ['/tmp', 'www.example.com', '/tmp/', 'www.example.com/'];
        yield ['/tmp/', 'www.example.com', '/tmp/', 'www.example.com/'];
        yield ['/tmp', 'www.example.com/', '/tmp/', 'www.example.com/'];
        yield ['/tmp/', 'www.example.com/', '/tmp/', 'www.example.com/'];
    }

    /**
     * @dataProvider dataProviderForGetters
     */
    public function testGetters(string $path, string $url, string $expectingPath, string $expectingUrl): void
    {
        $path = new Path($path, $url);

        $this->assertEquals($expectingPath, $path->path());
        $this->assertEquals($expectingUrl, $path->url());
    }

    public function testNotExistingPath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Path must point to existing directory');

        new Path('/tmp/test/not/existing/folder', 'www.example.com');
    }
}
