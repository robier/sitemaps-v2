<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Processor;

use Generator;
use Iterator;
use Robier\SiteMaps\Common;
use Robier\SiteMaps\Contract;
use Robier\SiteMaps\File;
use Robier\SiteMaps\Path;

class IndexFile implements Contract\Processor
{
    use Common\PathTrait {
        path as protected traitPath;
    }
    use Common\ChunkTrait;
    use Common\XMLTrait;

    protected const MAX_ITEMS     = 50000;
    protected const MAX_FILE_SIZE = 52000000; // ~50MB
    protected const EXTENSION     = 'xml';

    protected const SUFFIX = 'index';

    protected $forceSiteMapIndexes;
    protected $indent;
    protected $dateFormat;
    protected $name;
    protected $pathData;
    protected $styleSheet;

    public function __construct(Path $path, string $name, string $dateFormat = 'Y-m-d', bool $indent = true, string $styleSheet = null)
    {
        $this->pathData = $path;
        $this->name = $name;
        $this->dateFormat = $dateFormat;
        $this->indent = $indent;
        $this->styleSheet = $styleSheet;
    }

    protected function generateSiteMapIndex(string $path, \Iterator $siteMaps): Generator
    {
        $xml = $this->XMLOpen($path, $this->indent, $this->styleSheet);

        $xml->startElement('sitemapindex');
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        /** @var File\SiteMap $siteMap */
        $count = 0;
        foreach ($siteMaps as $siteMap) {
            yield $siteMap;

            $xml->startElement('sitemap');
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

    public function apply(Iterator $items): Generator
    {
        $index = 0;
        $path = $this->path($index);

        $siteMaps = $this->chunk($items, $path);

        foreach ($siteMaps as $links) {

            $indexFile = new File\SiteMapIndex(
                0,
                new Path(dirname($path), $this->pathData->url()),
                basename($path)
            );

            $generator = $this->generateSiteMapIndex($path, $links);
            /** @var File\SiteMap $siteMap */
            foreach ($generator as $siteMap) {
                yield $siteMap->changeIndex($indexFile);
            }

            yield $indexFile->changeCount((int)$generator->getReturn());

            ++$index;
            $path = $this->path($index);
        }
    }

    protected function path(int $index): string
    {
        return $this->traitPath($this->pathData->path(), sprintf('%s-%s', $this->name, static::SUFFIX), $index);
    }
}
