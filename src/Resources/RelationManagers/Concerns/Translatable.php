<?php

namespace JeffersonGoncalves\FilamentTranslatable\Resources\RelationManagers\Concerns;

use JeffersonGoncalves\FilamentTranslatable\FilamentTranslatablePlugin;
use JeffersonGoncalves\FilamentTranslatable\Resources\Concerns\HasActiveLocaleSwitcher;

trait Translatable
{
    use HasActiveLocaleSwitcher;

    public function mountTranslatable(): void
    {
        $locales = $this->getTranslatableLocales();

        if (blank($this->activeLocale) || ! in_array($this->activeLocale, $locales)) {
            $this->activeLocale = $this->getDefaultTranslatableLocale();
        }
    }

    public function getTranslatableLocales(): array
    {
        /** @var FilamentTranslatablePlugin $plugin */
        $plugin = filament('filament-translatable');

        return $plugin->getDefaultLocales();
    }

    public function getDefaultTranslatableLocale(): string
    {
        $locales = $this->getTranslatableLocales();

        return $locales[0] ?? app()->getLocale();
    }

    public function getActiveTableLocale(): ?string
    {
        return $this->activeLocale;
    }

    protected function setActiveLocale(): void
    {
        $this->activeLocale = $this->getDefaultTranslatableLocale();
    }
}
