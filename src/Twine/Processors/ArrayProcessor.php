<?php

namespace JeroenGerits\Twine\Twine\Processors;

use JeroenGerits\Twine\Contracts\TwineInputProcessor;

class ArrayProcessor implements TwineInputProcessor
{
    protected StringProcessor $stringProcessor;

    public function __construct(?StringProcessor $stringProcessor = null)
    {
        $this->stringProcessor = $stringProcessor ?? new StringProcessor;
    }

    public function process(mixed $input): string
    {
        if ($input === null) {
            return '';
        }

        $flattened = $this->flatten((array) $input);
        $filtered = array_filter($flattened, fn ($item) => $item !== '');

        return implode(' ', array_values(array_unique($filtered, SORT_REGULAR)));
    }

    protected function flatten(array $array): array
    {
        $result = [];

        foreach ($array as $item) {
            if ($item === null || $item === false) {
                continue;
            }

            if (is_array($item)) {
                $result = array_merge($result, $this->flatten($item));

                continue;
            }

            if (is_bool($item)) {
                continue;
            }

            $trimmed = $this->stringProcessor->process($item);
            if ($trimmed !== '') {
                $result[] = $trimmed;
            }
        }

        return $result;
    }
}
