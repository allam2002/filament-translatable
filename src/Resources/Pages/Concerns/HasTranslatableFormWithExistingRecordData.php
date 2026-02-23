<?php

namespace JeffersonGoncalves\FilamentTranslatable\Resources\Pages\Concerns;

use Livewire\Attributes\Locked;

trait HasTranslatableFormWithExistingRecordData
{
    #[Locked]
    public array $otherLocaleData = [];

    protected function fillForm(): void
    {
        $this->activeLocale = $this->getDefaultTranslatableLocale();

        $record = $this->getRecord();
        $locales = static::getResource()::getTranslatableLocales();
        $translatableAttributes = static::getResource()::getTranslatableAttributes();

        $allLocaleData = [];

        foreach ($locales as $locale) {
            $localeData = [];

            foreach ($translatableAttributes as $attribute) {
                $localeData[$attribute] = $record->getTranslation($attribute, $locale, false);
            }

            $allLocaleData[$locale] = $localeData;
        }

        $activeLocaleData = $allLocaleData[$this->activeLocale] ?? [];
        unset($allLocaleData[$this->activeLocale]);
        $this->otherLocaleData = $allLocaleData;

        $nonTranslatableData = collect($record->attributesToArray())
            ->except($translatableAttributes)
            ->all();

        $this->form->fill(array_merge($nonTranslatableData, $activeLocaleData));
    }

    protected function getDefaultTranslatableLocale(): string
    {
        $resource = static::getResource();
        $defaultLocale = $resource::getDefaultTranslatableLocale();

        $record = $this->getRecord();

        if (! method_exists($record, 'getTranslation')) {
            return $defaultLocale;
        }

        $translatableAttributes = $resource::getTranslatableAttributes();

        if (empty($translatableAttributes)) {
            return $defaultLocale;
        }

        $firstAttribute = $translatableAttributes[0];

        $translation = $record->getTranslation($firstAttribute, $defaultLocale, false);

        if (filled($translation)) {
            return $defaultLocale;
        }

        $locales = $resource::getTranslatableLocales();

        foreach ($locales as $locale) {
            $translation = $record->getTranslation($firstAttribute, $locale, false);

            if (filled($translation)) {
                return $locale;
            }
        }

        return $defaultLocale;
    }
}
