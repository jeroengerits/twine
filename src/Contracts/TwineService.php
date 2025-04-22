<?php

namespace JeroenGerits\Twine\Contracts;

use Stringable;

interface TwineService extends Stringable
{
    public static function make(string|array $classes, bool $condition = true): self;

    public function add(string|array $classes, bool $condition = true): self;

    public function when(bool $condition, callable $callback): self;

    public function unless(bool $condition, callable $callback): self;

    public function merge(self $other): self;

    public function get(): string;

    public function toString(): string;

    public function toArray(): array;
}
