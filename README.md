# Twine

Twine is a Laravel utility class for fluently building CSS class name strings.

> Work-in-progress: do not use in production.

## Features

- Fluent interface
- Handles strings, arrays & nested arrays
- Supports conditional inputs
- Removes duplicates
- Zero dependencies

## Installation

```bash
composer require jeroengerits/twine
```

## Usage

### Basic Usage

```php
// Simple
twine('btn btn-primary');
// "btn btn-primary"

// Adding
twine('btn')->add('btn-lg');
// "btn btn-lg"

// Nesting
twine(['btn-primary', ['btn-lg', 'text-red']]);
// btn-primary btn-lg text-red

// Chaining
twine('btn')
    ->add('btn-primary')
    ->add('btn-large');
// btn btn-primary btn-large

// Conditionally
twine('btn')
    ->add('wow', true);
// btn wow 

twine('btn')
    ->add('wow', false);
// btn 

```

### Conditional Classes

```php


    
// Callback conditionally when `true`
$classes = twine('btn')
    ->when(true, function ($twine) {
        return $twine->add('btn-lg');
    });

// Callback conditionally when `false`
$classes = twine('btn')
    ->unless(true, function ($twine) {
        return $twine->add('btn-active');
    });
```

### Merging Classes

```php
$classes1 = twine('btn');
$classes2 = twine('btn-primary');

$merged = $classes1->merge($classes2);
echo $merged; // "btn btn-primary"
```

### Output Methods

```php
$classes = twine('btn btn-primary');

// Get as string
$string = $classes->toString(); // "btn btn-primary"

// Get as array
$array = $classes->toArray(); // ['btn', 'btn-primary']

// Use in string context
echo $classes; // "btn btn-primary"
```

## API Reference

### Static Methods

- `make(array|string|null $classes = '', ?TwineClassesBuilder $builder = null): self` - Create a new instance with optional initial classes and builder.

### Instance Methods

- `add(array|string|null $classes, bool $condition = true): self` - Add classes to the instance.
- `when(bool $condition, callable $callback): self` - Run callback if condition is true.
- `unless(bool $condition, callable $callback): self` - Run callback if condition is false.
- `merge(Twine $other): self` - Combine classes from another instance.
- `toString(): string` - Get classes as space-separated string.
- `toArray(): array` - Get classes as array.
- `get(): string` - Alias for toString().

## License

MIT License

## Contributing

Contributions are welcome! Feel free to submit a Pull Request or report issues on GitHub.
