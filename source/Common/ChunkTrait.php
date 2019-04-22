<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Common;

use Iterator;
use Robier\SiteMaps\Iterator\Chunk;

trait ChunkTrait
{
    protected function chunk(Iterator $items, &$path): Chunk
    {
        return new Chunk(
            $items,
            new Chunk\FileSize($path, $this->maxFileSize()),
            new Chunk\ItemCount($this->maxItems())
        );
    }

    protected abstract function maxItems(): int;

    protected abstract function maxFileSize(): int;
}
