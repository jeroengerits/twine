<?php

use JeroenGerits\Twine\Twine;
use JeroenGerits\Twine\Twine\Builders\SimpleClassesBuilder;

describe('Twine', function () {
    describe('API', function () {
        it('twine() function should return Twine instance', function () {
            expect(twine())->toBeInstanceOf(Twine::class);
        });

        it('twine function should accept string input', function (): void {
            expect(twine('bg-red-400'))->toBeInstanceOf(Twine::class);
        });

        it('twine function should accept array input', function (): void {
            expect(twine(['bg-red-400', 'text-green-500']))->toBeInstanceOf(Twine::class);
        });

        it('should use custom builder when provided', function () {
            $builder = SimpleClassesBuilder::make('custom-prefix');
            $twine = Twine::make('custom-suffix', $builder);
            expect($twine->toString())->toBe('custom-prefix custom-suffix');
        });

        it('should handle null input with custom builder', function () {
            $builder = SimpleClassesBuilder::make('base');
            $twine = Twine::make(null, $builder);
            expect($twine->toString())->toBe('base');
        });

        it('should convert to string using __toString', function () {
            $twine = Twine::make('btn');
            expect((string) $twine)->toBe('btn');
        });

        it('should handle empty string input', function () {
            $twine = Twine::make('');
            expect($twine->toString())->toBe('');
        });

        it('should handle empty array input', function () {
            $twine = Twine::make([]);
            expect($twine->toString())->toBe('');
        });

        it('should handle multiple empty strings in array', function () {
            $twine = Twine::make(['', '', '']);
            expect($twine->toString())->toBe('');
        });

        it('should preserve custom builder state across operations', function () {
            $builder = SimpleClassesBuilder::make('prefix');
            $twine1 = Twine::make('middle', $builder);
            $twine2 = Twine::make('suffix', $builder);

            expect($twine1->toString())->toBe('prefix middle');
            expect($twine2->toString())->toBe('prefix suffix');
        });

        it('should handle mixed whitespace and empty strings in array', function () {
            $twine = Twine::make(['  ', '', 'valid', "\t", 'class']);
            expect($twine->toString())->toBe('valid class');
        });

        it('should handle special characters in class names', function () {
            $twine = Twine::make(['bg-opacity-50%', 'text-[#123456]', 'w-1/2']);
            expect($twine->toString())->toBe('bg-opacity-50% text-[#123456] w-1/2');
        });
    });

    describe('Builder', function () {
        describe('Initialization', function () {
            it('creates a new instance using the static factory method', function () {
                expect(SimpleClassesBuilder::make())->toBeInstanceOf(SimpleClassesBuilder::class);
            });

            it('initializes with empty string when no input is provided', function () {
                $builder = SimpleClassesBuilder::make();
                expect($builder->toArray())->toBe(['']);
                expect($builder->toString())->toBe('');
            });

            it('initializes with string class', function () {
                $builder = SimpleClassesBuilder::make('btn');
                expect($builder->toArray())->toBe(['btn']);
                expect($builder->toString())->toBe('btn');
            });

            it('initializes with array of classes', function () {
                $builder = SimpleClassesBuilder::make(['btn', 'btn-primary']);
                expect($builder->toArray())->toBe(['btn', 'btn-primary']);
                expect($builder->toString())->toBe('btn btn-primary');
            });
        });

        describe('Adding Classes', function () {
            it('adds string class', function () {
                $builder = SimpleClassesBuilder::make('btn')
                    ->add('btn-primary');
                expect($builder->toString())->toBe('btn btn-primary');
            });

            it('adds array of classes', function () {
                $builder = SimpleClassesBuilder::make('btn')
                    ->add(['btn-primary', 'btn-lg']);
                expect($builder->toString())->toBe('btn btn-primary btn-lg');
            });

            it('removes duplicate classes', function () {
                $builder = SimpleClassesBuilder::make('btn')
                    ->add('btn')
                    ->add('btn-primary')
                    ->add('btn');
                expect($builder->toString())->toBe('btn btn-primary');
            });

            it('removes duplicate classes case-sensitively', function () {
                $builder = SimpleClassesBuilder::make('btn')
                    ->add('BTN')
                    ->add('btn-primary')
                    ->add('BTN');
                expect($builder->toString())->toBe('btn BTN btn-primary');
            });
        });

        describe('Whitespace Handling', function () {
            it('handles whitespace in class names', function () {
                $builder = SimpleClassesBuilder::make('  btn  ')
                    ->add('  btn-primary  ')
                    ->add('  btn  ');
                expect($builder->toString())->toBe('btn btn-primary');
            });

            it('handles whitespace and case sensitivity together', function () {
                $builder = SimpleClassesBuilder::make('  btn  ')
                    ->add('  BTN  ')
                    ->add('  btn-primary  ')
                    ->add('  BTN  ');
                expect($builder->toString())->toBe('btn BTN btn-primary');
            });

            it('handles multiple spaces and tabs in class names', function () {
                $builder = SimpleClassesBuilder::make("btn\t")
                    ->add("btn\t\tprimary")
                    ->add("btn\t")
                    ->add('  btn-primary  ');
                expect($builder->toString())->toBe('btn btn primary btn-primary');
            });
        });

        describe('Conditional Operations', function () {
            it('conditionally adds classes when condition is true', function () {
                $builder = SimpleClassesBuilder::make('btn')
                    ->add('btn-primary', true);
                expect($builder->toString())->toBe('btn btn-primary');
            });

            it('does not add classes when condition is false', function () {
                $builder = SimpleClassesBuilder::make('btn')
                    ->add('btn-primary', false);
                expect($builder->toString())->toBe('btn');
            });

            it('executes callback when condition is true', function () {
                $result = SimpleClassesBuilder::make('btn')
                    ->when(true, function ($builder) {
                        return $builder->add('btn-primary');
                    });
                expect($result->toString())->toBe('btn btn-primary');
            });

            it('does not execute callback when condition is false', function () {
                $result = SimpleClassesBuilder::make('btn')
                    ->when(false, function ($builder) {
                        return $builder->add('btn-primary');
                    });
                expect($result->toString())->toBe('btn');
            });

            it('executes callback when condition is false in unless', function () {
                $result = SimpleClassesBuilder::make('btn')
                    ->unless(false, function ($builder) {
                        return $builder->add('btn-primary');
                    });
                expect($result->toString())->toBe('btn btn-primary');
            });

            it('does not execute callback when condition is true in unless', function () {
                $result = SimpleClassesBuilder::make('btn')
                    ->unless(true, function ($builder) {
                        return $builder->add('btn-primary');
                    });
                expect($result->toString())->toBe('btn');
            });
        });

        describe('Builder Operations', function () {
            it('merges with another builder', function () {
                $builder1 = SimpleClassesBuilder::make('btn');
                $builder2 = SimpleClassesBuilder::make('btn-primary');

                $result = $builder1->merge($builder2);
                expect($result->toString())->toBe('btn btn-primary');
            });

            it('returns string using get method', function () {
                $builder = SimpleClassesBuilder::make('btn');
                expect($builder->get())->toBe('btn');
            });

            it('casts to string using __toString', function () {
                $builder = SimpleClassesBuilder::make('btn');
                expect((string) $builder)->toBe('btn');
            });
        });
    });
});
