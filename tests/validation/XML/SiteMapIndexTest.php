<?php

namespace Robier\SiteMaps\Test\Validation\XML;

use DateTime;
use Generator;
use Robier\SiteMaps\File\SiteMap;
use Robier\SiteMaps\Processor\IndexFile;
use Robier\SiteMaps\Test\Validation\Base;

/**
 * Class XMLSiteMapIndexTest
 *
 * @package Robier\SiteMaps\Test\Validation
 */
class SiteMapIndexTest extends Base
{
    use Assertions;

    const XSD = 'https://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd';

    public function testFile()
    {
        $writer = new IndexFile($this->fakePath(), 'test');

        /** @var SiteMap $file */
        foreach ($writer->apply($this->fakeSiteMapData(500)) as $file) {
            if ($file instanceof SiteMap) {
                continue;
            }

            $this->assertValidXMLStructure($file->fullPath(), static::XSD);
        }
    }

    protected function fakeSiteMapData(int $count): Generator
    {
        $dateTime = new DateTime();

        for ($i = 0; $i < $count; $i++) {
            yield new SiteMap(rand(10, 500), 'test', $this->fakePath(), "fake-sitemap-$i.xml", $dateTime);
        }
    }
}
