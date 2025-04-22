# Twine

A Laravel utility for building CSS class strings with a clean API.

> Work-in-progress: do not use in production.

## Installation

```bash
composer require jeroengerits/twine
```

## Basic Usage

```php
// Create with string
twine('btn btn-primary');                 // "btn btn-primary"

// Create with array
twine(['btn', 'btn-primary']);            // "btn btn-primary"

// Add classes
twine('btn')->add('btn-primary');         // "btn btn-primary"

// Add conditionally
twine('btn')->add('active', $isActive);   // "btn active" if $isActive is true
```

## Conditional Logic

```php
// Using when()
twine('btn')
    ->when(true, fn ($twine) => $twine->add('btn-lg'))
    ->when(true, fn ($twine) => $twine->add('btn-primary'));

// Using unless()
twine('btn')
    ->unless(true, fn ($twine) => $twine->add('active'));
```

## Combining Classes

```php
// Merge two instances
$button = twine('btn');
$variant = twine('btn-primary');

$button->merge($variant);                 // "btn btn-primary"
```

## Output Options

```php
$classes = twine('btn btn-primary');

// As string (multiple ways)
echo $classes;                            // "btn btn-primary"
$classes->toString();                     // "btn btn-primary"
$classes->get();                          // "btn btn-primary"

// As array
$classes->toArray();                      // ['btn', 'btn-primary']
```

## API Reference

### Creating Instances

- `twine(string|array|null $classes = null): TwineService`
  - Helper function to create a new instance
  - Accepts string, array, or null input

- `Twine::make(string|array $classes, bool $condition = true): TwineService`
  - Static constructor
  - Creates a new instance with optional condition

### Instance Methods

- `add(string|array $classes, bool $condition = true): TwineService`
  - Add one or more classes
  - Only adds if condition is true
  - Returns new instance

- `when(bool $condition, callable $callback): TwineService`
  - Run callback if condition is true
  - Callback receives current instance
  - Returns new instance

- `unless(bool $condition, callable $callback): TwineService`
  - Run callback if condition is false
  - Callback receives current instance
  - Returns new instance

- `merge(TwineService $other): TwineService`
  - Combine with another instance
  - Returns new instance

- `toString(): string`
  - Convert to space-separated string
  - Filters out empty values

- `toArray(): array`
  - Convert to array of classes
  - Filters out empty values

- `get(): string`
  - Alias for toString()

## Features

- Immutable operations (methods return new instances)
- Fluent interface for method chaining
- Handles nested arrays
- Removes empty values and duplicates
- Type-safe with PHP 8 types

## License

MIT License