<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Iterator;

use Iterator;
use Robier\SiteMaps\Contract;
use Robier\SiteMaps\File\SiteMapIndex;

class Chunk implements Iterator
{
    protected $items;
    protected $chunks;

    public function __construct(Iterator $items, Contract\Chunk $chunk, Contract\Chunk ...$chunks)
    {
        $this->items = $items;
        array_unshift($chunks, $chunk);
        $this->chunks = $chunks;
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        $position = 0;
        while ($this->validate($position, $this->items->current())) {
            yield $this->items->current();
            $this->items->next();
            ++$position;
        }
    }

    protected function validate(int $position, $currentItem): bool
    {
        if(!$this->items->valid()){
            return false;
        }

        /** @var Contract\Chunk $validate */
        foreach($this->chunks as $validate){
            if(!$validate->valid($position, $currentItem)){
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        // we are doing $iterator->next() in current() method
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->items->key();
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return $this->items->valid();
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        $this->items->rewind();
    }
}
