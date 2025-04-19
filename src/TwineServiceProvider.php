<?php

namespace JeroenGerits\Twine;

use JeroenGerits\Twine\Commands\TwineCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TwineServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('twine');
        // ->hasConfigFile()
        // ->hasViews()
        // ->hasMigration('create_twine_table')
        // ->hasCommand(TwineCommand::class);
    }
}
