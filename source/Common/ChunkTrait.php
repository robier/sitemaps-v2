<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Common;

use Iterator;
use Robier\SiteMaps\Iterator\FileChunk;

trait ChunkTrait
{
    protected function chunk(Iterator $items): FileChunk
    {
        return new FileChunk($items, $this->maxItems(), $this->maxFileSize());
    }

    protected abstract function maxItems(): int;

    protected abstract function maxFileSize(): int;
}
