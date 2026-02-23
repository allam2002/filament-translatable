<?php

namespace JeffersonGoncalves\FilamentTranslatable\Resources\Concerns;

use JeffersonGoncalves\FilamentTranslatable\FilamentTranslatablePlugin;

trait Translatable
{
    public static function getDefaultTranslatableLocale(): string
    {
        $locales = static::getTranslatableLocales();

        return $locales[0] ?? app()->getLocale();
    }

    public static function getTranslatableAttributes(): array
    {
        $model = static::getModel();

        if (! method_exists($model, 'getTranslatableAttributes')) {
            throw new \Exception("Model [{$model}] does not use the HasTranslations trait or does not define translatable attributes.");
        }

        return app($model)->getTranslatableAttributes();
    }

    public static function getTranslatableLocales(): array
    {
        /** @var FilamentTranslatablePlugin $plugin */
        $plugin = filament('filament-translatable');

        return $plugin->getDefaultLocales();
    }
}
