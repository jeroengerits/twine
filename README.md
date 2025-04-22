# Twine

**Twine is a Laravel utility class for fluently building CSS class name strings.**

## Features

- Fluent interface
- Supports Conditionals
- Zero dependencies

## Installation

```bash
composer require jeroengerits/twine
```

## Usage

### Basic Usage

```php
// Create with initial classes
$classes = Twime::make('btn')
    ->add('btn-primary')
    ->add('btn-lg');

echo $classes; // "btn btn-primary btn-lg"

// Or use the helper function
$classes = twine('btn')
    ->add('btn-primary');

echo $classes; // "btn btn-primary"
```

### Adding Classes

```php
// Add single class
$classes = twine('btn');

// Chain classes
$classes = twine('btn')
    ->add('btn-primary')
    ->add('btn-large');

// Add multiple classes
$classes = twine(['btn-primary', 'btn-lg']);

// Add conditionally
$classes = twine('btn')
    ->add('btn-disabled', $isDisabled);
```

### Conditional Classes

```php
// Add classes when condition is true
$classes = twine('btn')
    ->when($isLarge, function ($twine) {
        return $twine->add('btn-lg');
    });

// Add classes when condition is false
$classes = twine('btn')
    ->unless($isDisabled, function ($twine) {
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
