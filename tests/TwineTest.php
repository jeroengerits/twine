<?php

use JeroenGerits\Twine\Twine;
use JeroenGerits\Twine\Twine\Processors\ArrayProcessor;
use JeroenGerits\Twine\Twine\Processors\StringProcessor;

describe('Twine', function () {
    it('creates a new instance using the static factory method', function () {
        expect(Twine::make())->toBeInstanceOf(Twine::class);
    });

    it('creates a new instance using the helper function', function () {
        expect(twine())->toBeInstanceOf(Twine::class);
    });

    it('returns an empty string when no input is provided', function () {
        expect(twine()->build())->toBeString();
    });

    it('returns a single class name when provided as a string', function () {
        expect(twine('bg-red-500')->build())
            ->toBeString()
            ->toBe('bg-red-500');
    });

    it('combines multiple class names from an array into a space-separated string', function () {
        expect(twine(['bg-red-500', 'text-white'])->build())
            ->toBeString()
            ->toBe('bg-red-500 text-white');
    });

    it('flattens and combines class names from nested arrays', function () {
        expect(twine(['bg-red-500', ['text-white', 'text-black']])->build())
            ->toBeString()
            ->toBe('bg-red-500 text-white text-black');
    });

    it('returns an empty string for null, empty array, or no input', function () {
        expect(twine()->build())->toBeString()->toBe('');
        expect(twine(null)->build())->toBeString()->toBe('');
        expect(twine([])->build())->toBeString()->toBe('');
    });

    it('removes empty strings and trims whitespace from class names', function () {
        expect(twine(['', 'bg-red-500', '', 'text-white', ''])->build())
            ->toBeString()
            ->toBe('bg-red-500 text-white');

        expect(twine(['  bg-red-500  ', '  text-white  '])->build())
            ->toBeString()
            ->toBe('bg-red-500 text-white');
    });

    it('removes duplicate class names from the input', function () {
        $input = ['bg-red-500', 'bg-red-500', 'text-white', 'text-white'];
        $expected = 'bg-red-500 text-white';

        expect(twine($input)->build())
            ->toBeString()
            ->toBe($expected);
    });

    it('removes duplicate class names from nested arrays', function () {
        $input = ['bg-red-500', ['text-white', 'text-white'], 'text-white', ['text-red-500', 'text-white']];
        $expected = 'bg-red-500 text-white text-red-500';

        expect(twine($input)->build())
            ->toBeString()
            ->toBe($expected);
    });

    it('handles deeply nested arrays of class names', function () {
        $input = ['bg-red-500', ['text-white', ['font-bold', ['hover:bg-blue-500']]]];
        $expected = 'bg-red-500 text-white font-bold hover:bg-blue-500';

        expect(twine($input)->build())
            ->toBeString()
            ->toBe($expected);
    });

    it('handles mixed input types in nested arrays', function () {
        $input = ['bg-red-500', ['text-white', null, 'font-bold', ['']]];
        $expected = 'bg-red-500 text-white font-bold';

        expect(twine($input)->build())
            ->toBeString()
            ->toBe($expected);
    });

    it('always returns a string regardless of input type', function () {
        $input = [
            'string' => fn () => fake()->word(),
            'array' => fn () => array_map(fn () => fake()->word(), range(1, fake()->numberBetween(1, 5))),
            'null' => fn () => null,
            'empty' => fn () => '',
        ];

        $randomInput = $input[array_rand($input)]();

        expect(twine($randomInput)->build())->toBeString();
    });

    it('handles randomly generated nested arrays of class names', function () {
        $generateNestedArray = function ($depth = 0) use (&$generateNestedArray) {
            if ($depth > 3) {
                return fake()->word();
            }

            return array_map(function () use ($depth, $generateNestedArray) {
                return fake()->boolean(70)
                    ? fake()->word()
                    : $generateNestedArray($depth + 1);
            }, range(1, fake()->numberBetween(1, 3)));
        };

        $nestedArray = $generateNestedArray();
        $result = twine($nestedArray)->build();

        expect($result)->toBeString()->not->toBeEmpty();
    });

    it('removes duplicates in randomly generated arrays of class names', function () {
        $words = array_map(fn () => fake()->word(), range(1, 10));
        $array = array_map(fn () => $words[array_rand($words)], range(1, 20));

        $result = twine($array)->build();
        $uniqueWords = array_unique(explode(' ', $result));

        expect(count(explode(' ', $result)))->toBe(count($uniqueWords));
    });

    it('chains multiple class names using the with method', function () {
        $classes = twine('bg-red-400')
            ->with('text-green-300')
            ->build();

        expect($classes)->toBe('bg-red-400 text-green-300');
    });

    it('adds conditional class names when the condition is true', function () {
        $classes = twine()
            ->with('bg-blue-300')
            ->when(true, function ($twine) {
                $twine->with('text-green-300');
            })
            ->build();

        expect($classes)
            ->toBeString()
            ->toBe('bg-blue-300 text-green-300');
    });

    it('skips conditional class names when the condition is false', function () {
        $classes = twine()
            ->with('bg-blue-300')
            ->when(false, function ($twine) {
                $twine->with('text-green-300');
            })
            ->build();

        expect($classes)
            ->toBeString()
            ->toBe('bg-blue-300');
    });

    it('adds multiple conditional class names when the condition is true', function () {
        $classes = twine()
            ->with('bg-blue-300')
            ->when(true, function ($twine) {
                $twine->with('text-green-300', 'text-red-300');
            })
            ->build();

        expect($classes)
            ->toBeString()
            ->toBe('bg-blue-300 text-green-300 text-red-300');
    });

    it('adds multiple conditional class names from nested arrays when the condition is true', function () {
        $classes = twine()
            ->with('bg-blue-300')
            ->when(true, function ($twine) {
                $twine->with(['text-green-300', 'text-red-300'], 'border-2 border-black');
            })
            ->build();

        expect($classes)
            ->toBeString()
            ->toBe('bg-blue-300 text-green-300 text-red-300 border-2 border-black');
    });

    it('handles nested conditional class name additions', function () {
        $classes = twine()
            ->with('bg-blue-300')
            ->when(true, function ($twine) {
                $twine->with('text-green-300')
                    ->when(true, function ($twine) {
                        $twine->with('hover:bg-green-100');
                    });
            })
            ->build();

        expect($classes)
            ->toBeString()
            ->toBe('bg-blue-300 text-green-300 hover:bg-green-100');
    });

    it('handles numeric values as class names', function () {
        expect(twine(123)->build())->toBe('123');
        expect(twine(0)->build())->toBe('0');
        expect(twine([123, 'bg-red-500', 0])->build())->toBe('123 bg-red-500 0');
    });

    it('handles mixed types in arrays of class names', function () {
        $input = [
            'bg-red-500',
            123,
            null,
            true,
            false,
            '',
            'text-white',
            ['nested', 'array'],
            0,
        ];
        expect(twine($input)->build())->toBe('bg-red-500 123 text-white nested array 0');
    });

    it('preserves the order of class names in the input', function () {
        $input = ['z-10', 'absolute', 'top-0', 'left-0'];
        expect(twine($input)->build())->toBe('z-10 absolute top-0 left-0');
    });

    it('chains multiple with method calls correctly', function () {
        $classes = twine('bg-red-500')
            ->with('text-white')
            ->with('p-4')
            ->with('m-2')
            ->build();

        expect($classes)->toBe('bg-red-500 text-white p-4 m-2');
    });

    it('handles empty arrays in nested structures', function () {
        $input = [
            'bg-red-500',
            [],
            ['text-white', []],
            'p-4',
        ];
        expect(twine($input)->build())->toBe('bg-red-500 text-white p-4');
    });

    it('handles complex nested conditionals with arrays', function () {
        $classes = twine('base')
            ->when(true, function ($t) {
                return $t->with(['nested', 'array'])
                    ->when(true, function ($t) {
                        return $t->with('deep-nested');
                    });
            })
            ->build();

        expect($classes)->toBe('base nested array deep-nested');
    });

    it('handles deeply nested arrays with mixed types', function () {
        $input = [
            'level1',
            [
                'level2',
                [
                    'level3',
                    null,
                    true,
                    false,
                    '',
                    ['level4', ['level5']],
                ],
            ],
        ];
        expect(twine($input)->build())->toBe('level1 level2 level3 level4 level5');
    });

    it('handles custom string processor in array processor', function () {
        $stringProcessor = new StringProcessor;
        $arrayProcessor = new ArrayProcessor($stringProcessor);

        $result = $arrayProcessor->process(['test', '  trimmed  ']);
        expect($result)->toBe('test trimmed');
    });

    it('handles null input in array processor', function () {
        $arrayProcessor = new ArrayProcessor;
        expect($arrayProcessor->process(null))->toBe('');
    });

    it('handles boolean values in array processor', function () {
        $arrayProcessor = new ArrayProcessor;
        expect($arrayProcessor->process([true, false]))->toBe('');
    });

    it('handles empty arrays in array processor', function () {
        $arrayProcessor = new ArrayProcessor;
        expect($arrayProcessor->process([]))->toBe('');
    });

    it('handles null input in string processor', function () {
        $stringProcessor = new StringProcessor;
        expect($stringProcessor->process(null))->toBe('');
    });

    it('handles boolean values in string processor', function () {
        $stringProcessor = new StringProcessor;
        expect($stringProcessor->process(true))->toBe('');
        expect($stringProcessor->process(false))->toBe('');
    });

    it('handles numeric values in string processor', function () {
        $stringProcessor = new StringProcessor;
        expect($stringProcessor->process(123))->toBe('123');
        expect($stringProcessor->process(0))->toBe('0');
        expect($stringProcessor->process(3.14))->toBe('3.14');
    });

    it('handles string values in string processor', function () {
        $stringProcessor = new StringProcessor;
        expect($stringProcessor->process('test'))->toBe('test');
        expect($stringProcessor->process('  test  '))->toBe('test');
        expect($stringProcessor->process(''))->toBe('');
    });

    it('handles prefix and suffix', function () {
        $classes = twine()
            ->with('bg-red-500')
            ->prefix('hover:', fn ($twine) => $twine->with('text-white'))
            ->suffix('/50', fn ($twine) => $twine->with('text-white'))
            ->build();

        expect($classes)->toBe('bg-red-500 hover:text-white text-white/50');
    });

    it('handles empty inputs in prefix and suffix', function () {
        $classes = twine('base')
            ->prefix('hover:', fn ($twine) => $twine->with(''))
            ->suffix('/50', fn ($twine) => $twine->with(null))
            ->build();

        expect($classes)->toBe('base');
    });

    it('handles arrays in prefix and suffix', function () {
        $classes = twine('base')
            ->prefix('hover:', fn ($twine) => $twine->with(['bg-blue-500', 'text-white']))
            ->suffix('/50', fn ($twine) => $twine->with(['opacity', 'blur']))
            ->build();

        expect($classes)->toBe('base hover:bg-blue-500 hover:text-white opacity/50 blur/50');
    });

    it('handles nested prefix and suffix calls', function () {
        $classes = twine('base')
            ->prefix('hover:', fn ($twine) => $twine
                ->with('bg-blue-500')
                ->prefix('focus:', fn ($twine) => $twine
                    ->with('ring-2'))
                ->suffix('/80', fn ($twine) => $twine->with('ring-2')))
            ->build();

        expect($classes)->toBe('base hover:bg-blue-500 hover:focus:ring-2 hover:ring-2/80');
    });

    it('handles numeric values in prefix and suffix', function () {
        $classes = twine('base')
            ->prefix('scale-', fn ($twine) => $twine->with([100, 200]))
            ->suffix('%', fn ($twine) => $twine->with([50, 75]))
            ->build();

        expect($classes)->toBe('base scale-100 scale-200 50% 75%');
    });

    it('handles mixed types in prefix and suffix', function () {
        $classes = twine('base')
            ->prefix('p-', fn ($twine) => $twine->with([1, 'sm', 'lg']))
            ->suffix('-important', fn ($twine) => $twine->with(['bold', 0, 'italic']))
            ->build();

        expect($classes)->toBe('base p-1 p-sm p-lg bold-important 0-important italic-important');
    });

    it('throws exception for invalid input types', function () {
        expect(fn () => new Twine(true))->toThrow(\InvalidArgumentException::class);
        expect(fn () => Twine::make(true))->toThrow(\InvalidArgumentException::class);
        expect(fn () => twine()->with(true))->toThrow(\InvalidArgumentException::class);
    });

    it('handles deeply nested arrays', function () {
        $classes = twine('base')
            ->with([
                'level1',
                [
                    'level2',
                    [
                        'level3',
                        ['level4'],
                    ],
                ],
            ])
            ->build();

        expect($classes)->toBe('base level1 level2 level3 level4');
    });

    it('removes duplicate class names with unique method', function () {
        $classes = twine('base')
            ->with('text-red', 'text-red', ['text-blue', 'text-blue'])
            ->unique()
            ->build();

        expect($classes)->toBe('base text-red text-blue');
    });

    it('sorts class names alphabetically', function () {
        $classes = twine('base')
            ->with('z-10', 'a-1', 'm-5')
            ->sort()
            ->build();

        expect($classes)->toBe('base a-1 m-5 z-10');
    });

    it('combines all features', function () {
        $classes = twine('base')
            ->with(['text-red', 'text-blue'])
            ->prefix('hover:', fn ($twine) => $twine->with('bg-gray'))
            ->suffix('/50', fn ($twine) => $twine->with('opacity'))
            ->unique()
            ->sort()
            ->build();

        expect($classes)->toBe('base hover:bg-gray opacity/50 text-blue text-red');
    });
});
