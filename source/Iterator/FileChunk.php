<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Iterator;

use Generator;
use Iterator;

class FileChunk implements Iterator
{
    protected $iterator;
    protected $maxItems;
    protected $maxBytes;

    protected $file;

    public function __construct(Iterator $iterator, int $maxItems, int $maxBytes)
    {
        $this->iterator = $iterator;
        $this->maxBytes = $maxBytes - 100; // remove 100 bytes so we do not spill over $maxBites
        $this->maxItems = $maxItems;
    }

    public function file(string $path): self
    {
        $this->file = $path;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return Generator
     */
    public function current(): Generator
    {
        $lineNumber = 0;
        while ($this->validate($lineNumber)) {
            yield $this->iterator->current();
            $this->iterator->next();
            ++$lineNumber;
        }
    }

    protected function validate(int $lineNumber): bool
    {
        if (!$this->iterator->valid()) {
            return false;
        }

        if ($lineNumber >= $this->maxItems) {
            return false;
        }

        // file not provided
        if (null === $this->file) {
            throw new \InvalidArgumentException('File property not defined!');
        }

        clearstatcache(true, $this->file);
        if (!is_readable($this->file)) {
            // file does not exists
            return true;
        }

        return filesize($this->file) < $this->maxBytes;
    }

    /**
     * @inheritdoc
     *
     * @see FileChunk::current()
     */
    public function next(): void
    {
        // we are doing $iterator->next() in current() method
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->iterator->key();
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
    public function rewind(): void
    {
        $this->iterator->rewind();
    }
}
