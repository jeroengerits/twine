<?php

declare(strict_types=1);

namespace JeroenGerits\Twine;

readonly class Twine
{
    public function __construct(
        protected array $classesInput
    ) {}

    public static function make(mixed ...$input)
    {
        return new self($input);
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
