<?php

namespace JeffersonGoncalves\FilamentTranslatable\Resources\Pages\ListRecords\Concerns;

use JeffersonGoncalves\FilamentTranslatable\Resources\Concerns\HasActiveLocaleSwitcher;

trait Translatable
{
    use HasActiveLocaleSwitcher;

    public function mountTranslatable(): void
    {
        $this->activeLocale = static::getResource()::getDefaultTranslatableLocale();
    }

    public function getTranslatableLocales(): array
    {
        return static::getResource()::getTranslatableLocales();
    }

    public function getActiveTableLocale(): ?string
    {
        return $this->activeLocale;
    }
}
