# Twine

**Twine is a PHP utility for fluently building CSS class name strings with readability and flexibility in mind.**

Perfect for TailwindCSS, Laravel, and component-based workflows

## Installation

```bash
composer require jeroengerits/twine
```

## Quick Start

```php
use Jeroengerits\Twine\Twine;

// Basic usage
Twine::make('text-xl')->build();
// returns 'text-xl'

// Multiple classes
Twine::make('text-xl', 'bg-red-500')->build();
// returns 'text-xl bg-red-500'

// Using the helper function (if available)
twine('text-xl', 'bg-red-500')->build();
// returns 'text-xl bg-red-500'
```

## Core Features

### Adding Classes

Twine can handle both strings and arrays of class names. It also supports nested arrays, which will be flattened into a single list of classes.

```php
// Using an array
Twine::make(['text-xl', 'bg-red-500'])->build();
// returns 'text-xl bg-red-500'

// Using nested arrays
Twine::make(['text-xl', ['bg-red-500', 'font-bold']])->build();
// returns 'text-xl bg-red-500 font-bold'
```

### Conditional Classes

```php
$isActive = true;
Twine::make('text-xl')
    ->when($isActive, fn($twine) => $twine->with(['bg-blue-500', ['border', 'rounded']]))
    ->build();
// returns 'text-xl bg-blue-500 border rounded'
```

### Prefixes and Suffixes

```php
// Prefix
Twine::make('text-white')
    ->prefix('hover:', fn($twine) => $twine->with(['bg-blue-500', ['font-bold']]))
    ->build();
// returns 'text-white hover:bg-blue-500 hover:font-bold'

// Suffix
Twine::make('bg-blue')
    ->suffix('/50', fn($twine) => $twine->with(['text-white', ['shadow-lg']]))
    ->build();
// returns 'bg-blue text-white/50 shadow-lg/50'
```

## API Reference

### Static Methods
- `make(mixed ...$input): self` - Create a new Twine instance with optional initial classes. Accepts strings, arrays, and nested arrays.

### Instance Methods
- `with(mixed ...$input): self` - Add class names to the collection. Supports strings, arrays, and nested arrays.
- `when(bool $condition, Closure $callback): self` - Conditionally add class names using a callback if the condition is true.
- `prefix(string $prefix, Closure $callback): self` - Add a prefix to class names generated within the callback.
- `suffix(string $suffix, Closure $callback): self` - Add a suffix to class names generated within the callback.
- `build(): string` - Build and return the final class name string.

## Features

- Fluent interface for clean, readable code
- Conditional class management using callbacks
- Support for strings, arrays, and nested arrays
- Prefix and suffix support for dynamic class generation
- Laravel integration (if applicable)
- Zero dependencies
- Type safety

## License

MIT License

## Contributing

Contributions are welcome! Feel free to submit a Pull Request or report issues on GitHub.

---

This README now clearly states that Twine supports nested arrays as input and provides examples demonstrating this functionality.