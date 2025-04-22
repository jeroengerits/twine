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
    });
});
