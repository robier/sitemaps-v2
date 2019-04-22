<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Iterator\Chunk;

use Robier\SiteMaps\Contract;

class ItemCount implements Contract\Chunk
{
    protected $maxItems;

    public function __construct(int $maxItems)
    {
        $this->maxItems = $maxItems;
    }

    public function valid(int $currentItem, $item): bool
    {
        return $currentItem < $this->maxItems;
    }
}
