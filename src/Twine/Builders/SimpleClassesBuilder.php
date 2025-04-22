<?php

declare(strict_types=1);

namespace JeroenGerits\Twine\Twine\Builders;

use JeroenGerits\Twine\Contracts\TwineClassesBuilder;

/**
 * Builds and manages CSS class names.
 * Each operation creates a new instance, keeping the original unchanged.
 */
readonly class SimpleClassesBuilder implements TwineClassesBuilder
{
    /**
     * Creates a new builder with the given classes.
     * If no classes are given, starts with an empty string.
     */
    public function __construct(
        private array $classes = ['']
    ) {}

    /**
     * Cleans up a class name by removing extra spaces.
     */
    private function normalizeClass(string $class): string
    {
        return trim(preg_replace('/\s+/', ' ', $class));
    }

    /**
     * Creates a new instance with the given classes.
     * Removes empty strings from the list.
     */
    private function withClasses(array $classes): self
    {
        $filtered = array_filter($classes, fn (string $class): bool => $class !== '');

        return new self($filtered ?: ['']);
    }

    /**
     * Creates a new builder with the given classes.
     * Handles strings, arrays, and null values.
     */
    public static function make(array|string|null $classes = '', bool $condition = true): self
    {
        if (! $condition) {
            return new self;
        }

        if ($classes === null) {
            return new self;
        }

        if (is_array($classes)) {
            $classes = array_filter($classes, fn (string $class): bool => trim($class) !== '');
        }

        if ($classes === [] || $classes === '') {
            return new self;
        }

        $normalizedClasses = is_array($classes)
            ? array_map(fn (string $class): string => trim(preg_replace('/\s+/', ' ', $class)), $classes)
            : [trim(preg_replace('/\s+/', ' ', $classes))];

        $filtered = array_filter($normalizedClasses, fn (string $class): bool => $class !== '');

        return new self($filtered ?: ['']);
    }

    /**
     * Adds new classes to the builder.
     * Returns a new instance with the added classes.
     */
    public function add(array|string|null $classes, bool $condition = true): self
    {
        if (! $condition) {
            return $this;
        }

        if ($classes === null) {
            return $this;
        }

        if (is_array($classes)) {
            $classes = array_filter($classes, fn (string $class): bool => trim($class) !== '');
        }

        if ($classes === [] || $classes === '') {
            return $this;
        }

        $newClasses = is_array($classes)
            ? array_map(fn (string $class): string => $this->normalizeClass($class), $classes)
            : [$this->normalizeClass($classes)];

        $filtered = array_filter($newClasses, fn (string $class): bool => $class !== '');

        if (count($this->classes) === 1 && $this->classes[0] === '') {
            return new self($filtered ?: ['']);
        }

        return $this->withClasses([...$this->classes, ...$filtered]);
    }

    /**
     * Runs a callback if the condition is true.
     * Returns a new instance with the result.
     */
    public function when(bool $condition, callable $callback): self
    {
        if (! $condition) {
            return $this;
        }

        $result = $callback($this);

        if (! $result instanceof static) {
            return $this;
        }

        return $result;
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
     * Combines classes from another builder.
     * Returns a new instance with all classes.
     */
    public function merge(TwineClassesBuilder $other): self
    {
        $otherClasses = $other->toArray();
        $mergedClasses = [...$this->classes, ...$otherClasses];

        return $this->withClasses($mergedClasses);
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
        $classes = array_filter(array_unique($this->classes), fn (string $class): bool => $class !== '');

        return implode(' ', $classes ?: ['']);
    }

    /**
     * Returns the classes as an array.
     */
    public function toArray(): array
    {
        $filtered = array_filter(array_unique($this->classes), fn (string $class): bool => $class !== '');

        return $filtered ?: [''];
    }

    /**
     * Returns the classes as a string when used in string context.
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
