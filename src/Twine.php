<?php

declare(strict_types=1);

namespace JeroenGerits\Twine;

/**
 * Helps build CSS class names in a simple way.
 * Each operation creates a new instance, keeping the original unchanged.
 */
readonly class Twine
{
    public static function make(mixed $input)
    {
        return new self;
    }

    /**
     * Returns the classes as a space-separated string.
     */
    public function toString(): string
    {
        return '';
    }

    /**
     * Returns the classes as a string when used in string context.
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
