<?php

namespace Robier\SiteMaps\Test\Validation;


use DateTime;
use Generator;
use PHPUnit\Framework\TestCase;
use Robier\SiteMaps\Location;
use Robier\SiteMaps\Path;

class Base extends TestCase
{
    const TEST_DIRECTORY = '/tmp/site-map-testing';

    private function deleteDirectory(string $name)
    {
        $files = array_diff(scandir($name), ['.','..']);
        foreach ($files as $file) {
            (is_dir("$name/$file")) ? $this->deleteDirectory("$name/$file") : unlink("$name/$file");
        }
        return rmdir($name);
    }

    protected function setUp()
    {
        mkdir(static::TEST_DIRECTORY, 0777, true);
    }

    protected function tearDown()
    {
        $this->deleteDirectory(static::TEST_DIRECTORY);
    }

    protected function fakeProviderData(int $linkCount): Generator
    {
        $dateTime = new DateTime();
        $changeFrequencies = Location\ChangeFrequency::all();

        for($i = 0; $i < $linkCount; $i++){
            $link = 'http://example.com/' . $i;
            $randomPriority = new Location\Priority(mt_rand (0, 10) / 10);
            $changeFrequency = new Location\ChangeFrequency($changeFrequencies[array_rand($changeFrequencies)]);

            yield new Location(
                $link,
                $i % 2 === 0 ? $randomPriority : null,
                $i % 3 === 0 ? $changeFrequency : null,
                $i % 5 === 0 ? $dateTime : null
            );
        }
    }

    protected function fakePath(): Path
    {
        return new Path(static::TEST_DIRECTORY, 'http://example.com/');
    }
}
