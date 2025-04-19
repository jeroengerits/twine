<?php

namespace JeroenGerits\Twine\Contracts;

interface TwineInputProcessor
{
    public function process(mixed $input): string;
}
