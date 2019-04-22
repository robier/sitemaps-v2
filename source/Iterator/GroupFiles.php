<?php

namespace Robier\SiteMaps\Iterator;

use Iterator;
use Robier\SiteMaps\File\SiteMap;

class GroupFiles implements Iterator
{
    protected $iterator;
    protected $group = null;

    public function __construct(Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        while ($this->valid()) {

            $item = $this->iterator->current();

            $currentGroup = $item instanceof SiteMap ? $item->group() : null;

            if ($currentGroup !== $this->group) {
                $this->group = $currentGroup;
                break;
            }

            yield $this->group => $this->iterator->current();
            $this->iterator->next();
        }
    }

    /**
     * @inheritdoc
     */
    public function next(): void
    {

    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->group;
    }

    /**
     * @inheritdoc
     */
    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        // TODO: Implement rewind() method.
    }
}
