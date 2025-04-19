<?php

namespace JeroenGerits\Twine\Twine\Processors;

use JeroenGerits\Twine\Contracts\TwineInputProcessor;

class StringProcessor implements TwineInputProcessor
{
    public function process(mixed $input): string
    {
        if ($input === null || $input === false) {
            return '';
        }

        if (is_bool($input)) {
            return '';
        }

        if (is_numeric($input)) {
            return (string) $input;
        }

        return trim((string) $input);
    }
}
