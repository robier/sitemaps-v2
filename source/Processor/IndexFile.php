<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Processor;

use Generator;
use Iterator;
use Robier\SiteMaps\Common;
use Robier\SiteMaps\File;
use Robier\SiteMaps\Contract;
use Robier\SiteMaps\Path;

class IndexFile implements Contract\Processor
{
    use Common\PathTrait;
    use Common\ChunkTrait;
    use Common\XMLTrait;

    protected const MAX_ITEMS     = 50000;
    protected const MAX_FILE_SIZE = 52000000; // ~50MB
    protected const EXTENSION     = 'xml';

    protected const SUFFIX = 'index';

    protected $forceSiteMapIndexes;
    protected $indent;
    protected $dateFormat;
    protected $suffix;
    protected $pathData;

    public function __construct(Path $data, string $suffix = '-index', string $dateFormat = 'Y-m-d', bool $indent = true)
    {
        $this->pathData = $data;
        $this->indent = $indent;
        $this->dateFormat = $dateFormat;
        $this->suffix = $suffix;
    }

    protected function generateSiteMapIndex(string $path, \Iterator $siteMaps): Generator
    {
        $xml = $this->XMLOpen($path, $this->indent);

        $xml->startElement('SiteMapIndexContract');
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        /** @var File\SiteMap $siteMap */
        $count = 0;
        foreach ($siteMaps as $siteMap) {
            $siteMap->changeSiteMapIndexFlag(true);
            yield $siteMap;

            $xml->startElement('url');
            $xml->writeElement('loc', $siteMap->fullUrl());

            if ($siteMap->lastModified()) {
                $xml->writeElement('lastmod', $siteMap->lastModified()->format($this->dateFormat));
            }

            $xml->endElement();
            ++$count;
        }

        $this->XMLClose($xml);

        return $count;
    }

    protected function extension(): string
    {
        return static::EXTENSION;
    }

    protected function maxItems(): int
    {
        return static::MAX_ITEMS;
    }

    protected function maxFileSize(): int
    {
        return static::MAX_FILE_SIZE;
    }

    public function apply(Iterator $items, string $group): Generator
    {
        $siteMaps = $this->chunk($items);

        $index = 0;
        $path = $this->path($this->pathData->path(), $group . $this->suffix, $index);

        foreach ($siteMaps->file($path) as $links) {
            $generator = $this->generateSiteMapIndex($path, $links);
            yield from $generator; // provide all siteMap files
            yield new File\SiteMapIndex(
                $generator->getReturn(),
                new Path(dirname($path), $this->pathData->url()),
                basename($path)
            );

            ++$index;
        }
    }
}
