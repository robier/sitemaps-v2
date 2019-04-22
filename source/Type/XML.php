<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Type;

use Iterator;
use Robier\SiteMaps\Common;
use Robier\SiteMaps\Location;

class XML extends Base
{
    use Common\XMLTrait;

    protected const MAX_ITEMS     = 50000;
    protected const MAX_FILE_SIZE = 52000000; // ~50MB
    protected const EXTENSION     = 'xml';

    protected $indent;
    protected $dateFormat;
    protected $styleSheet;

    public function __construct(string $dateFormat = 'Y-m-d', bool $indent = true, ?string $styleSheet = null)
    {
        parent::__construct();

        $this->dateFormat = $dateFormat;
        $this->indent = $indent;
        $this->styleSheet = $styleSheet;
    }

    protected function generateFile(string $fullPath, string $group, Iterator $chunk): int
    {
        $xml = $this->XMLOpen($fullPath, $this->indent, $this->styleSheet);

        $xml->startElement('urlset');
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        $count = 0;
        /** @var Location $location */
        foreach ($chunk as $location) {
            $xml->startElement('url');
            $xml->writeElement('loc', $location->url());

            if (!is_null($location->lastModified())) {
                $xml->writeElement('lastmod', $location->lastModified()->format($this->dateFormat));
            }

            if (!is_null($location->changeFrequency())) {
                $xml->writeElement('changefreq', $location->changeFrequency()->value());
            }

            if (!is_null($location->priority())) {
                $xml->writeElement('priority', (string)$location->priority()->value());
            }

            $xml->endElement();
            ++$count;
        }

        $this->XMLClose($xml);

        return $count;
    }
}
