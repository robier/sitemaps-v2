<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Type;

use DateTime;
use Iterator;
use Robier\SiteMaps\Common;
use Robier\SiteMaps\Location;

class RSS extends Base
{
    use Common\XMLTrait;

    protected const MAX_ITEMS     = 50000;
    protected const MAX_FILE_SIZE = 52000000; // ~50MB
    protected const EXTENSION     = 'rss';

    protected $indent;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $link;
    /**
     * @var string
     */
    protected $description;

    public function __construct(string $title, string $link, string $description, bool $indent = true)
    {
        parent::__construct();

        $this->title = $title;
        $this->link = $link;
        $this->description = $description;
        $this->indent = $indent;
    }

    protected function generateFile(string $fullPath, string $group, Iterator $chunk): int
    {
        $xml = $this->XMLOpen($fullPath, $this->indent);

        $xml->startElement('rss');
        $xml->writeAttribute('version', '2.0');

        $xml->startElement('channel');

        $xml->writeElement('title', $this->title);
        $xml->writeElement('link', $this->link);
        $xml->writeElement('description', $this->description);

        $count = 0;
        /** @var Location $location */
        foreach ($chunk as $location) {
            $xml->startElement('item');
            $xml->writeElement('link', $location->url());

            if (!is_null($location->lastModified())) {
                $xml->writeElement('pubDate', $location->lastModified()->format(DateTime::RFC822));
            }

            $xml->endElement();
            ++$count;
        }

        $this->XMLClose($xml);

        return $count;
    }
}
