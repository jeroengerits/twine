<?php

declare(strict_types=1);

namespace JeroenGerits\Twine;

use Arr;

readonly class Twine
{
    public function __construct(
        protected array $inputArr
    ) {}

    public static function make(mixed ...$input)
    {
        return new self($input);
    }

    public function toArray(): array
    {
        return Arr::flatten($this->inputArr);
    }

    public function toString(): string
    {
        return '';
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
