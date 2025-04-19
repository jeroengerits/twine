<?php

namespace JeroenGerits\Twine;

use Closure;
use JeroenGerits\Twine\Contracts\TwineInputProcessor;
use JeroenGerits\Twine\Twine\Processors\ArrayProcessor;
use JeroenGerits\Twine\Twine\Processors\StringProcessor;

/**
 * Twine is a fluent PHP utility for building CSS class name strings.
 *
 * This class provides a clean, chainable interface for composing conditional and dynamic class names.
 * It supports various input types including strings, arrays, and null values.
 */
class Twine
{
    /**
     * The processor used to handle the input types.
     */
    protected TwineInputProcessor $processor;

    /**
     * Collection of input values to be processed.
     */
    protected array $inputs = [];

    /**
     * Create a new Twine instance.
     *
     * @param  mixed  $input  Initial class name(s) to add
     *
     * @throws \InvalidArgumentException If input is not a string, array, or null
     */
    public function __construct(mixed $input = null)
    {
        if ($input !== null && ! is_string($input) && ! is_array($input)) {
            throw new \InvalidArgumentException('Input must be a string, array, or null.');
        }

        if ($input !== null) {
            $this->inputs[] = $input;
        }

        $this->processor = $this->resolveProcessor($input);
    }

    /**
     * Create a new Twine instance with optional initial classes.
     *
     * @param  mixed  ...$input  Initial class name(s) to add
     *
     * @throws \InvalidArgumentException If any input is not a string, array, or null
     */
    public static function make(mixed ...$input): self
    {
        foreach ($input as $item) {
            if ($item !== null && ! is_string($item) && ! is_array($item)) {
                throw new \InvalidArgumentException('Input must be a string, array, or null.');
            }
        }

        return new self(count($input) === 1 ? $input[0] : $input);
    }

    /**
     * Add class names to the collection.
     *
     * @param  mixed  ...$input  Class name(s) to add
     *
     * @throws \InvalidArgumentException If any input is not a string, array, or null
     */
    public function with(mixed ...$input): self
    {
        foreach ($input as $item) {
            if ($item !== null && ! is_string($item) && ! is_array($item)) {
                throw new \InvalidArgumentException('Input must be a string, array, or null.');
            }

            if ($item === null) {
                continue;
            }

            if (is_string($item)) {
                $this->inputs[] = $item;
            } else {
                $this->inputs = array_merge($this->inputs, $this->flattenArray($item));
                $this->processor = new ArrayProcessor(new StringProcessor);
            }
        }

        return $this;
    }

    /**
     * Conditionally add class names using a callback.
     *
     * @param  bool  $condition  Condition to evaluate
     * @param  Closure  $callback  Callback to execute if condition is true
     */
    public function when(bool $condition, Closure $callback): self
    {
        if ($condition) {
            $callback($this);
        }

        return $this;
    }

    /**
     * Add a prefix to class names.
     *
     * @param  string  $prefix  The prefix to add
     * @param  Closure  $callback  Callback to execute with the prefixed classes
     */
    public function prefix(string $prefix, Closure $callback): self
    {
        $originalInputs = $this->inputs;
        $this->inputs = [];

        $callback($this);

        /** @var array<array-key, mixed> $newInputs */
        $newInputs = $this->inputs;
        $this->inputs = $originalInputs;

        /** @var array<array-key, mixed> $processedInputs */
        $processedInputs = [];

        foreach ($newInputs as $input) {
            if ($input === null || $input === '') {
                continue;
            }

            if (is_string($input)) {
                $classes = explode(' ', $input);
                $processedInputs[] = implode(' ', array_map(fn ($class) => $prefix.$class, $classes));

                continue;
            }

            $processedInputs[] = array_map(fn ($class) => $prefix.(string) $class, (array) $input);
        }

        $this->inputs = array_merge($this->inputs, $processedInputs);

        return $this;
    }

    /**
     * Add a suffix to class names.
     *
     * @param  string  $suffix  The suffix to add
     * @param  Closure  $callback  Callback to execute with the suffixed classes
     */
    public function suffix(string $suffix, Closure $callback): self
    {
        $originalInputs = $this->inputs;
        $this->inputs = [];

        $callback($this);

        /** @var array<array-key, mixed> $newInputs */
        $newInputs = $this->inputs;
        $this->inputs = $originalInputs;

        /** @var array<array-key, mixed> $processedInputs */
        $processedInputs = [];

        foreach ($newInputs as $input) {
            if ($input === null || $input === '') {
                continue;
            }

            if (is_string($input)) {
                $classes = explode(' ', $input);
                $processedInputs[] = implode(' ', array_map(fn ($class) => $class.$suffix, $classes));

                continue;
            }

            $processedInputs[] = array_map(fn ($class) => (string) $class.$suffix, (array) $input);
        }

        $this->inputs = array_merge($this->inputs, $processedInputs);

        return $this;
    }

    /**
     * Remove duplicate class names from the collection.
     */
    public function unique(): self
    {
        $this->inputs = array_unique($this->flattenArray($this->inputs));

        return $this;
    }

    /**
     * Sort class names alphabetically.
     */
    public function sort(): self
    {
        $flattened = $this->flattenArray($this->inputs);
        $base = array_shift($flattened);
        sort($flattened, SORT_STRING);
        array_unshift($flattened, $base);
        $this->inputs = $flattened;

        return $this;
    }

    /**
     * Build and return the final class string.
     */
    public function build(): string
    {
        return empty($this->inputs)
            ? ''
            : $this->processor->process($this->inputs);
    }

    /**
     * Resolve the appropriate processor based on input type.
     *
     * @param  mixed  $input  Input to process
     */
    private function resolveProcessor(mixed $input): TwineInputProcessor
    {
        if ($input === null) {
            return new ArrayProcessor(new StringProcessor);
        }

        if (is_string($input)) {
            return new StringProcessor;
        }

        return new ArrayProcessor(new StringProcessor);
    }

    /**
     * Flatten a nested array into a single array of strings.
     *
     * @param  array<array-key, mixed>  $array  Array to flatten
     * @return array<array-key, string>
     */
    protected function flattenArray(array $array): array
    {
        $result = [];
        array_walk_recursive($array, function ($value) use (&$result) {
            if ($value !== null && $value !== '') {
                $result[] = (string) $value;
            }
        });

        return $result;
    }
}
