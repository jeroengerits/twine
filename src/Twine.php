<?php

declare(strict_types=1);

namespace JeroenGerits\Twine;

use JeroenGerits\Twine\Contracts\TwineService;

readonly class Twine implements TwineService
{
    public function __construct(
        protected array $input
    ) {}

    public static function make(array|string $classes, bool $condition = true): TwineService
    {
        if (! $condition) {
            return new self([]);
        }

        return new self([$classes]);
    }

    public function add(array|string $classes, bool $condition = true): TwineService
    {
        if (! $condition) {
            return $this;
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
        return implode(' ', $this->toArray());
    }

    public function toArray(): array
    {
        return array_filter($this->input);
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
