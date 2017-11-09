<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Common;

use XMLWriter;

trait XMLTrait
{
    protected function XMLOpen(string $path, bool $indent = true): XMLWriter
    {
        $xml = new XMLWriter();
        $xml->openURI($path);

        $xml->startDocument('1.0', 'UTF-8');
        $xml->setIndent($indent);

        return $xml;
    }

    protected function XMLClose(XMLWriter $xml): void
    {
        // close all open elements
        while ($xml->endElement()) ;
        // close file
        $xml->endDocument();
    }
}
