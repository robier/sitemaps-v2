<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Iterator\Chunk;

use Robier\SiteMaps\Contract;

class FileSize implements Contract\Chunk
{
    protected $path;
    protected $maxBytes;

    public function __construct(&$path, int $maxBytes)
    {
        $this->path = $path;
        $this->maxBytes = $maxBytes;
    }

    public function valid(int $currentItem, $item): bool
    {
        clearstatcache(true, $this->path);
        if (!is_readable($this->path)) {
            $currentSize = 0;
        }else{
            $currentSize = filesize($this->path);
        }

        return $currentSize < $this->maxBytes;
    }
}
