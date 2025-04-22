<?php

declare(strict_types=1);

namespace JeroenGerits\Twine;

use JeroenGerits\Twine\Contracts\TwineService;

readonly class Twine implements TwineService
{
    public function __construct(
        protected array $input
    ) {}

    public static function make(array|string|null $classes = null, bool $condition = true): TwineService
    {
        if (! $condition) {
            return new self([]);
        }

        if ($classes === null) {
            return new self([]);
        }

        if (is_string($classes)) {
            return new self([array_filter(explode(' ', $classes))]);
        }

        return new self([$classes]);
    }

    public function add(array|string $classes, bool $condition = true): TwineService
    {
        if (! $condition) {
            return $this;
        }

        if (is_string($classes)) {
            $classes = array_filter(explode(' ', $classes));
        }

        return new self([...$this->input, $classes]);
    }

    public function when(bool $condition, callable $callback): TwineService
    {
        return $condition ? $callback($this) : $this;
    }

    public function unless(bool $condition, callable $callback): TwineService
    {
        return $this->when(! $condition, $callback);
    }

    public function merge(TwineService $other): TwineService
    {
        return new self([...$this->input, ...$other->toArray()]);
    }

    public function get(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return implode(' ', array_map(function ($item) {
            return is_array($item) ? implode(' ', $item) : $item;
        }, $this->toArray()));
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this->input as $item) {
            if (is_array($item)) {
                $result = [...$result, ...$item];
            } else {
                $result[] = $item;
            }
        }

        return array_values(array_filter($result, fn ($item) => ! empty($item) && is_string($item)));
    }

    public function getInput(): array
    {
        return $this->input;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
