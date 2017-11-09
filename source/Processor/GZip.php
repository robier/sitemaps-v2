<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Processor;

use Generator;
use Iterator;
use Robier\SiteMaps\Contract;

class GZip implements Contract\Processor
{
    protected const EXTENSION = 'gz';
    protected const COMPRESSION_LEVEL = 5;
    protected const READ_CHUNK = 4096; // ~4 kB

    protected $compressionLevel;

    public function __construct(int $compressionLevel = self::COMPRESSION_LEVEL)
    {
        if (!extension_loaded('zlib')) {
            throw new \LogicException('To use gzip compression you need to have zlib php extension, none detected');
        }

        if ($compressionLevel <= 0 || $compressionLevel > 9) {
            throw new \InvalidArgumentException('Compression level must be inside 0 and 9');
        }

        $this->compressionLevel = $compressionLevel;
    }

    protected function compress(Contract\File $item, int $level): string
    {
        $name = sprintf('%s.%s', $item->name(), static::EXTENSION);

        if (!is_readable($item->fullPath())) {
            throw new \InvalidArgumentException(sprintf('File %s does not exist', $item->fullPath()));
        }

        $mode = sprintf('wb%d', $level);

        $gzipHandle = gzopen($item->path() . $name, $mode);
        $fileHandle = fopen($item->fullPath(), 'rb');

        while (!feof($fileHandle)) {
            gzwrite($gzipHandle, fread($fileHandle, static::READ_CHUNK));
        }

        gzclose($gzipHandle);
        fclose($fileHandle);

        return $name;
    }

    public function apply(Iterator $items, string $group): Generator
    {
        /** @var Contract\File $item */
        foreach ($items as $item) {
            $name = $this->compress($item, $this->compressionLevel);
            // delete old file
            unlink($item->fullPath());

            yield $item->changeName($name);
        }
    }
}
