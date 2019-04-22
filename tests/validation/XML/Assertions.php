<?php

namespace Robier\SiteMaps\Test\Validation\XML;

use XMLReader;

trait Assertions
{
    public static function assertValidXMLStructure(string $XMLPath, string $XSDPath)
    {
        libxml_use_internal_errors(true);

        $reader = new XMLReader;
        $reader->open($XMLPath);
        $reader->setSchema($XSDPath);

        while ($reader->read()) ;

        $hasErrors = (bool)libxml_get_errors();

        if ($hasErrors) {
            libxml_get_errors();
        }

        static::assertFalse($hasErrors, 'XML file is not complied with XSD: ' . $XSDPath);
    }
}
