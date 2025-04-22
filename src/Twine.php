<?php

declare(strict_types=1);

namespace JeroenGerits\Twine;

use JeroenGerits\Twine\Contracts\TwineClassesBuilder;
use JeroenGerits\Twine\Twine\Builders\SimpleClassesBuilder;

/**
 * Helps build CSS class names in a simple way.
 * Each operation creates a new instance, keeping the original unchanged.
 */
readonly class Twine
{
    /**
     * Creates a new instance with the given builder.
     * Uses SimpleClassesBuilder if no builder is given.
     */
    public function __construct(
        private TwineClassesBuilder $builder = new SimpleClassesBuilder
    ) {}

    /**
     * Creates a new instance with the given classes.
     * Handles strings, arrays, and null values.
     */
    public static function make(array|string|null $classes = '', ?TwineClassesBuilder $builder = null): self
    {
        $builder = $builder ?? new SimpleClassesBuilder;

        return new self($builder->add($classes));
    }

    /**
     * Adds new classes to the instance.
     * Returns a new instance with the added classes.
     */
    public function add(array|string|null $classes, bool $condition = true): self
    {
        return new self($this->builder->add($classes, $condition));
    }

    /**
     * Runs a callback if the condition is true.
     * Returns a new instance with the result.
     */
    public function when(bool $condition, callable $callback): self
    {
        return new self($this->builder->when($condition, fn () => $callback($this)->builder));
    }

    /**
     * Runs a callback if the condition is false.
     * Returns a new instance with the result.
     */
    public function unless(bool $condition, callable $callback): self
    {
        return $this->when(! $condition, $callback);
    }

    /**
     * Combines classes from another instance.
     * Returns a new instance with all classes.
     */
    public function merge(Twine $other): self
    {
        return new self($this->builder->merge($other->builder));
    }

    /**
     * Returns the classes as a string.
     */
    public function get(): string
    {
        return $this->toString();
    }

    /**
     * Returns the classes as a space-separated string.
     */
    public function toString(): string
    {
        return $this->builder->toString();
    }

    /**
     * Returns the classes as an array.
     */
    public function toArray(): array
    {
        return $this->builder->toArray();
    }

    /**
     * Returns the classes as a string when used in string context.
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
