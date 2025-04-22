<?php

namespace JeroenGerits\Twine\Twine;

use Illuminate\Support\Collection;
use Stringable;

class ClassNameCollection extends Collection implements Stringable
{
    public function toString(): string
    {
        return twine($this->flatten()->unique()->all())->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
