<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Type;

use Iterator;
use LogicException;
use Robier\SiteMaps\Common;
use Robier\SiteMaps\Contract;
use Robier\SiteMaps\Path;
use Robier\SiteMaps\File\SiteMap;

abstract class Base implements Contract\Type
{
    use Common\ChunkTrait;
    use Common\PathTrait;

    protected const MAX_ITEMS     = null;
    protected const MAX_FILE_SIZE = null;
    protected const EXTENSION     = null;

    public function __construct()
    {
        if (is_null(static::MAX_ITEMS)) {
            throw new LogicException('Constant MAX_ITEMS not set in class ' . get_class(static::class));
        }

        if (is_null(static::MAX_FILE_SIZE)) {
            throw new LogicException('Constant MAX_FILE_SIZE not set in class ' . get_class(static::class));
        }

        if (is_null(static::EXTENSION)) {
            throw new LogicException('Constant EXTENSION not set in class ' . get_class(static::class));
        }
    }

    public function write(Path $data, string $group, Iterator $links): Iterator
    {
        $index = 0;
        $chunks = $this->chunk($links);

        $fullPath = $this->path($data->path(), $group, $index);

        foreach ($chunks->file($fullPath) as $chunk) {
            $linkCount = $this->generateFile($fullPath, $group, $chunk);
            yield new SiteMap($linkCount, $group, new Path(dirname($fullPath), $data->url()), basename($fullPath));

            ++$index;
            $fullPath = $this->path($data->path(), $group, $index);
        }
    }

    protected abstract function generateFile(string $fullPath, string $group, Iterator $chunk): int;

    protected function maxItems(): int
    {
        return static::MAX_ITEMS;
    }

    protected function maxFileSize(): int
    {
        return static::MAX_FILE_SIZE;
    }

    protected function extension(): string
    {
        return static::EXTENSION;
    }
}
