<?php

namespace JeffersonGoncalves\FilamentTranslatable\Actions\Concerns;

use JeffersonGoncalves\FilamentTranslatable\FilamentTranslatablePlugin;

trait HasTranslatableLocaleOptions
{
    protected function setTranslatableLocaleOptions(): static
    {
        $this->options(function (): array {
            $livewire = $this->getLivewire();

            if (! method_exists($livewire, 'getTranslatableLocales')) {
                return [];
            }

            $locales = $livewire->getTranslatableLocales();

            /** @var FilamentTranslatablePlugin $plugin */
            $plugin = filament('filament-translatable');

            $options = [];

            foreach ($locales as $locale) {
                $options[$locale] = $plugin->getLocaleLabel($locale) ?? $locale;
            }

            return $options;
        });

        return $this;
    }
}
