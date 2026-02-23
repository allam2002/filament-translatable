<?php

namespace JeffersonGoncalves\FilamentTranslatable;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentTranslatableServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-translatable';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews();
    }
}
