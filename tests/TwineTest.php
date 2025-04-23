<?php

use JeroenGerits\Twine\Twine;

describe('Twine', function () {
    describe('API', function () {
        it('should return Twine instance', function () {
            expect(Twine::make('btn'))->toBeInstanceOf(Twine::class);
        });

        it('should accept string input', function (): void {
            expect(Twine::make('bg-red-400'))->toBeInstanceOf(Twine::class);
        });

        it('should accept array input', function (): void {
            expect(Twine::make(['bg-red-400', 'text-green-500']))->toBeInstanceOf(Twine::class);
        });

        it('should accept nested array input', function (): void {
            $twine = Twine::make('bg-red-400')
                ->add(['text-green-500', 'font-bold']);
            expect($twine)->toBeInstanceOf(Twine::class);
        });

        it('return input as-is', function (): void {
            $input = ['bg-red-400'];
            expect(Twine::make($input)->getInput())->toBe([$input]);
        });

        it('can add classes conditionally', function (): void {
            $twine = Twine::make('btn')
                ->add('active', true)
                ->add('disabled', false);
            expect($twine->toString())->toBe('btn active');
        });

        it('can merge instances', function (): void {
            $twine1 = Twine::make('btn');
            $twine2 = Twine::make('active');
            expect($twine1->merge($twine2)->toString())->toBe('btn active');
        });

        it('handles empty input', function (): void {
            expect(Twine::make('')->toString())->toBe('');
            expect(Twine::make([])->toString())->toBe('');
            expect(Twine::make(null)->toString())->toBe('');
        });

        it('handles multiple spaces in string input', function (): void {
            expect(Twine::make('btn  btn-primary')->toString())->toBe('btn btn-primary');
        });

        it('handles when condition', function (): void {
            $twine = Twine::make('btn')
                ->when(true, fn ($t) => $t->add('active'))
                ->when(false, fn ($t) => $t->add('disabled'));
            expect($twine->toString())->toBe('btn active');
        });

        it('handles unless condition', function (): void {
            $twine = Twine::make('btn')
                ->unless(false, fn ($t) => $t->add('active'))
                ->unless(true, fn ($t) => $t->add('disabled'));
            expect($twine->toString())->toBe('btn active');
        });

        it('handles complex nested conditions', function (): void {
            $twine = Twine::make('btn')
                ->when(true, fn ($t) => $t->add('active'))
                ->unless(false, fn ($t) => $t->add('primary'))
                ->add('large', true)
                ->add('small', false);
            expect($twine->toString())->toBe('btn active primary large');
        });

        it('handles multiple merges', function (): void {
            $twine1 = Twine::make('btn');
            $twine2 = Twine::make('active');
            $twine3 = Twine::make('primary');
            expect($twine1->merge($twine2)->merge($twine3)->toString())->toBe('btn active primary');
        });

        it('handles string conversion', function (): void {
            $twine = Twine::make('btn')->add('active');
            expect((string) $twine)->toBe('btn active');
            expect($twine->__toString())->toBe('btn active');
            expect($twine->get())->toBe('btn active');
        });

        it('handles array conversion', function (): void {
            $twine = Twine::make('btn')->add('active');
            expect($twine->toArray())->toBe(['btn', 'active']);
        });

        it('handles empty classes in array', function (): void {
            $twine = Twine::make(['btn', '', null, 'active']);
            expect($twine->toString())->toBe('btn active');
        });

        it('should have match api that conditionally returns a value', function () {
            $twine = Twine::make('text-red')
                ->match('xs', [
                    'xs' => 'text-xs',
                    'lg' => 'text-lg',
                ]);

            expect($twine->toString())->toBe('text-red text-xs');
        });

        it('should not add class when key is not found in match', function () {
            $twine = Twine::make('text-red')
                ->match('md', [
                    'xs' => 'text-xs',
                    'lg' => 'text-lg',
                ]);

            expect($twine->toString())->toBe('text-red');
        });

        it('should handle empty haystack in match', function () {
            $twine = Twine::make('text-red')
                ->match('xs', []);

            expect($twine->toString())->toBe('text-red');
        });

        it('should handle multiple matches', function () {
            $twine = Twine::make('text-red')
                ->match('xs', [
                    'xs' => 'text-xs',
                    'lg' => 'text-lg',
                ])
                ->match('lg', [
                    'xs' => 'text-xs',
                    'lg' => 'text-lg',
                ]);

            expect($twine->toString())->toBe('text-red text-xs text-lg');
        });

        it('should handle array values in match', function () {
            $twine = Twine::make('text-red')
                ->match('xs', [
                    'xs' => ['text-xs', 'font-bold'],
                    'lg' => 'text-lg',
                ]);

            expect($twine->toString())->toBe('text-red text-xs font-bold');
        });

        it('should handle string values in match', function () {
            $twine = Twine::make('text-red')
                ->match('lg', [
                    'xs' => 'text-xs',
                    'lg' => 'text-lg',
                ]);

            expect($twine->toString())->toBe('text-red text-lg');
        });

        it('should handle empty string values in match', function () {
            $twine = Twine::make('text-red')
                ->match('xs', [
                    'xs' => '',
                    'lg' => 'text-lg',
                ]);

            expect($twine->toString())->toBe('text-red');
        });

        it('should handle non-string keys in match', function () {
            $twine = Twine::make('text-red')
                ->match(1, [
                    1 => 'text-xs',
                    2 => 'text-lg',
                ]);

            expect($twine->toString())->toBe('text-red text-xs');
        });

    });
});
