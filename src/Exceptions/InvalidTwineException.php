<?php

namespace JeroenGerits\Twine\Exceptions;

use InvalidArgumentException;

class InvalidTwineException extends InvalidArgumentException
{
    public static function invalidInputType(): self
    {
        return new self('Input must be a string, array, or null.');
    }
}
