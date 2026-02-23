<?php

namespace JeffersonGoncalves\FilamentTranslatable\Resources\Concerns;

use JeffersonGoncalves\FilamentTranslatable\FilamentTranslatableContentDriver;

trait HasActiveLocaleSwitcher
{
    public ?string $activeLocale = null;

    public function getActiveFormsLocale(): ?string
    {
        if (! method_exists($this, 'getTranslatableLocales')) {
            return null;
        }

        if (! in_array($this->activeLocale, $this->getTranslatableLocales())) {
            return null;
        }

        return $this->activeLocale;
    }

    public function getActiveActionsLocale(): ?string
    {
        return $this->activeLocale;
    }

    public function getFilamentTranslatableContentDriver(): ?string
    {
        return FilamentTranslatableContentDriver::class;
    }
}
