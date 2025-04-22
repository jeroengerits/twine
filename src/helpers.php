<?php

use JeroenGerits\Twine\Contracts\TwineService;
use JeroenGerits\Twine\Twine;

if (! function_exists('twine')) {
    function twine(array|string|null $input = null): TwineService
    {
        return Twine::make($input ?? '', true);
    }
}
