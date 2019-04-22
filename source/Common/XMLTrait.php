<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Common;

use XMLWriter;

trait XMLTrait
{
    protected function XMLOpen(string $path, bool $indent = true, ?string $styleSheet = null): XMLWriter
    {
        $xml = new XMLWriter();
        $xml->openURI($path);
        $xml->setIndent($indent);

        $xml->startDocument('1.0', 'UTF-8');

        // @todo add comment so it's known
//        $xml->writeComment('Generated via ');

        if(!is_null($styleSheet)){
            $xml->writePI('xml-stylesheet', sprintf('type="text/xsl" href="%s"', $styleSheet));
        }

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
