<?php

declare(strict_types=1);

namespace Robier\SiteMaps;

use InvalidArgumentException;
use Iterator;
use Robier\SiteMaps\Contract;

class Generator implements \IteratorAggregate
{
    protected $default = [
        'type' => null,
        'path' => null,
    ];

    protected $processors = [];

    protected $dataProviders = [];

    /**
     * Generator constructor.
     *
     * @param Contract\Type $type
     * @param Path $path
     */
    public function __construct(Contract\Type $type, Path $path)
    {
        $this->default['type'] = $type;
        $this->default['path'] = $path;
    }

    /**
     * @param string $group
     * @param Contract\DataProvider $dataProvider
     * @param Contract\Middleware[] ...$middleware
     *
     * @return Generator
     */
    public function data(string $group, Contract\DataProvider $dataProvider, Contract\Middleware ...$middleware): self
    {
        if (isset($this->dataProviders[$group])) {
            throw new InvalidArgumentException('Duplicated data provider name ' . $group);
        }

        $this->dataProviders[$group] =
            [
                'provider'   => $dataProvider,
                'middleware' => $middleware,
            ];

        return $this;
    }

    public function types(array $groups, Contract\Type $writer, ?Path $pathData = null): self
    {
        foreach ($groups as $group) {
            $this->type($group, $writer, $pathData);
        }

        return $this;
    }

    public function type(string $group, Contract\Type $writer, ?Path $pathData = null): self
    {
        if (!isset($this->dataProviders[$group])) {
            throw new InvalidArgumentException('Group %s is not registered');
        }

        // will register some group as special type for example rss or text
        // if path not provided, default (global) path will be used

        $this->dataProviders[$group] =
            [
                'type' => $writer,
                'path' => $pathData,
            ];

        return $this;
    }

    /**
     * Processor gets all the files generated.
     * It's handy if you need to do anything with files, for example compress them.
     *
     * @param Contract\Processor $processor
     * @param Contract\Processor[] ...$processors
     *
     * @return Generator
     */
    public function processor(Contract\Processor $processor, Contract\Processor ...$processors): self
    {
        // we are forcing 1st parameter
        array_unshift($processors, $processor);

        foreach ($processors as $processor) {
            $this->processors[] = $processor;
        }

        return $this;
    }

    public function generate(): Iterator
    {
        if (empty($this->dataProviders)) {
            throw new \LogicException('Can not generate sitemaps as no data is provided');
        }

        $generators = [];

        foreach ($this->dataProviders as $group => $data) {

            /** @var Contract\DataProvider $dataProvider */
            $dataProvider = $data['provider'];

            $generator = $dataProvider->fetch();

            /** @var Contract\Middleware $middleware */
            foreach ($data['middleware'] as $middleware) {
                $generator = $middleware->apply($generator);
            }

            $generators[] = $this->resolveWriter($generator, $group);
        }

        $leveledGenerators = $this->levelGenerators(...$generators);

        /** @var Contract\Processor $processor */
        foreach ($this->processors as $processor) {
            $leveledGenerators = $processor->apply($leveledGenerators);
        }

        yield from $leveledGenerators;
    }

    protected function levelGenerators(iterable ...$iterators): \Generator
    {
        foreach ($iterators as $iterator) {
            yield from $iterator;
        }
    }

    protected function resolveWriter(Iterator $generator, string $group): Iterator
    {
        // globals
        $writer = $this->default['type'];
        $path = $this->default['path'];

        // check if there is a possible custom type/path per group
        if (isset($this->dataProviders[$group]['type'])) {
            $writer = $this->dataProviders[$group]['type'];
        }

        if (isset($this->dataProviders[$group]['path'])) {
            $path = $this->dataProviders[$group]['path'];
        }

        return $writer->write($path, $group, $generator);
    }

    /**
     * @inheritdoc
     */
    public function getIterator(): Iterator
    {
        return $this->generate();
    }
}
