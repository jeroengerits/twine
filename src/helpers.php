<?php

use JeroenGerits\Twine\Twine;

if (! function_exists('twine')) {
    function twine(string|array|null $input = null): Twine
    {
        return Twine::make($input);
    }
}
