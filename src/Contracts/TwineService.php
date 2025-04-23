<?php

namespace JeroenGerits\Twine\Contracts;

use Stringable;

interface TwineService extends Stringable
{
    /**
     * Create a new Twine instance.
     *
     * @param  string|array|null  $classes  The initial CSS classes
     * @param  bool  $condition  Only add classes if condition is true
     */
    public static function make(string|array|null $classes, bool $condition = true): self;

    /**
     * Add CSS classes to the existing instance.
     *
     * @param  string|array  $classes  The CSS classes to add
     * @param  bool  $condition  Only add classes if condition is true
     */
    public function add(string|array $classes, bool $condition = true): self;

    /**
     * Add classes conditionally using a callback.
     *
     * @param  bool  $condition  The condition to check
     * @param  callable  $callback  Callback that receives the Twine instance
     */
    public function when(bool $condition, callable $callback): self;

    /**
     * Add classes conditionally using a callback when condition is false.
     *
     * @param  bool  $condition  The condition to check
     * @param  callable  $callback  Callback that receives the Twine instance
     */
    public function unless(bool $condition, callable $callback): self;

    /**
     * Merge another Twine instance into this one.
     *
     * @param  self  $other  The Twine instance to merge
     */
    public function merge(self $other): self;

    /**
     * Match a key against a map of classes and add the matching classes.
     *
     * @param  string  $needle  The key to match
     * @param  array  $haystack  Map of keys to CSS classes
     */
    public function match(string $needle, array $haystack): self;

    /**
     * Get the final CSS classes string.
     */
    public function get(): string;

    /**
     * Convert the CSS classes to a string.
     */
    public function toString(): string;

    /**
     * Convert the CSS classes to an array.
     */
    public function toArray(): array;
}
