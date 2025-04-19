<?php

namespace JeroenGerits\Twine\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JeroenGerits\Twine\Twine
 */
class Twine extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JeroenGerits\Twine\Twine::class;
    }
}
