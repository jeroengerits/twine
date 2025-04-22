# Twine

Twine is a Laravel utility for fluently building CSS class name strings.

> Work-in-progress: do not use in production.

## Installation

```bash
composer require jeroengerits/twine
```

## Usage

### Basic Examples

```php
// Simple usage
twine('btn btn-primary');

// Add classes
twine('btn')->add('btn-lg');

// Nested arrays
twine(['btn-primary', ['btn-lg', 'text-red']]);

// Chain methods
twine('btn')
    ->add('btn-primary')
    ->add('btn-large');
```

### Conditional Classes

```php
// Simple condition
twine('btn')
    ->add('active', $isActive);

// Callback when true
twine('btn')
    ->when($isLarge, function ($twine) {
        return $twine->add('btn-lg');
    });

// Callback when false
twine('btn')
    ->unless($isDisabled, function ($twine) {
        return $twine->add('btn-active');
    });
```

### Combining Classes

```php
$classes1 = twine('btn');
$classes2 = twine('btn-primary');

$classes1->merge($classes2);
```

### Output

```php
$classes = twine('btn btn-primary');

// As string
$classes->toString(); // "btn btn-primary"

// As array
$classes->toArray(); // ['btn', 'btn-primary']

// String context
echo $classes; // "btn btn-primary"
```

## API

### Static Methods

- `make(array|string|null $classes = '', ?TwineClassesBuilder $builder = null): self` - Create new instance

### Instance Methods

- `add(array|string|null $classes, bool $condition = true): self` - Add classes
- `when(bool $condition, callable $callback): self` - Add if true
- `unless(bool $condition, callable $callback): self` - Add if false
- `merge(Twine $other): self` - Combine with another instance
- `toString(): string` - Get as string
- `toArray(): array` - Get as array
- `get(): string` - Alias for toString()

## License

MIT License