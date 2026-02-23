<?php

namespace JeffersonGoncalves\FilamentTranslatable\Resources\Pages\ViewRecord\Concerns;

use JeffersonGoncalves\FilamentTranslatable\Resources\Concerns\HasActiveLocaleSwitcher;
use JeffersonGoncalves\FilamentTranslatable\Resources\Pages\Concerns\HasTranslatableFormWithExistingRecordData;
use JeffersonGoncalves\FilamentTranslatable\Resources\Pages\Concerns\HasTranslatableRecord;

trait Translatable
{
    use HasActiveLocaleSwitcher;
    use HasTranslatableFormWithExistingRecordData;
    use HasTranslatableRecord;

    public function getTranslatableLocales(): array
    {
        return static::getResource()::getTranslatableLocales();
    }

    public function updatingActiveLocale(): void
    {
        $this->oldActiveLocale = $this->activeLocale;
    }

    protected ?string $oldActiveLocale = null;

    public function updatedActiveLocale(?string $newActiveLocale = null): void
    {
        if (blank($this->oldActiveLocale)) {
            return;
        }

        $translatableAttributes = static::getResource()::getTranslatableAttributes();

        $existingLocaleData = $this->otherLocaleData[$this->activeLocale] ?? [];

        $nonTranslatableData = collect($this->form->getState())
            ->except($translatableAttributes)
            ->all();

        $this->form->fill(array_merge($nonTranslatableData, $existingLocaleData));
    }
}
