<?php

namespace Robier\SiteMaps\Test\Validation\XML;

use Robier\SiteMaps\File\SiteMap;
use Robier\SiteMaps\Test\Validation\Base;
use Robier\SiteMaps\Type\XML;

/**
 * Class XMLSiteMapTest
 *
 * @package Robier\SiteMaps\Test\Validation
 */
class SiteMapTest extends Base
{
    use Assertions;

    const XSD = 'https://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd';

    public function testFile()
    {
        $writer = new XML();

        $generator = $writer->write($this->fakePath(), 'test', $this->fakeProviderData(500));

        /** @var SiteMap $file */
        foreach($generator as $file){
            $this->assertValidXMLStructure($file->fullPath(), static::XSD);
        }
    }
}
