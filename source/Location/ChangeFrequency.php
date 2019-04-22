<?php

declare(strict_types=1);

namespace Robier\SiteMaps\Location;

use InvalidArgumentException;
use ReflectionClass;

/**
 * Class ChangeFrequency
 */
class ChangeFrequency
{
    const ALWAYS  = 'always';
    const HOURLY  = 'hourly';
    const DAILY   = 'daily';
    const WEEKLY  = 'weekly';
    const MONTHLY = 'monthly';
    const YEARLY  = 'yearly';
    const NEVER   = 'never';

    protected $value;

    protected static $cache;

    public function __construct(string $type)
    {
        static::cache();

        $type = strtolower($type);

        $this->validate($type);

        $this->value = $type;
    }

    protected static function cache()
    {
        if (static::$cache === null) {
            $reflection = new ReflectionClass(static::class);
            static::$cache = array_values($reflection->getConstants());
        }
    }

    protected function validate(string $type): void
    {
        if (!in_array($type, static::$cache)) {
            throw new InvalidArgumentException(sprintf('Invalid change frequency type %s', $type));
        }
    }

    public static function always(): self
    {
        return new static(static::ALWAYS);
    }

    public static function hourly(): self
    {
        return new static(static::HOURLY);
    }

    public static function daily(): self
    {
        return new static(static::DAILY);
    }

    public static function weekly(): self
    {
        return new static(static::WEEKLY);
    }

    public static function monthly(): self
    {
        return new static(static::MONTHLY);
    }

    public static function yearly(): self
    {
        return new static(static::YEARLY);
    }

    public static function never(): self
    {
        return new static(static::NEVER);
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function all(): array
    {
        static::cache();

        return static::$cache;
    }
}
