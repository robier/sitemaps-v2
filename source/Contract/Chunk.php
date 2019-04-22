<?php

namespace Robier\SiteMaps\Contract;


interface Chunk
{
    public function valid(int $currentItem, $item): bool;
}
