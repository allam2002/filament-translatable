<?php

namespace JeffersonGoncalves\FilamentTranslatable\Resources\Pages\EditRecord\Concerns;

use Illuminate\Database\Eloquent\Model;
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

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $translatableAttributes = static::getResource()::getTranslatableAttributes();

        $nonTranslatableData = collect($data)
            ->except($translatableAttributes)
            ->all();

        $record->fill($nonTranslatableData);

        foreach ($translatableAttributes as $attribute) {
            if (array_key_exists($attribute, $data)) {
                $record->setTranslation($attribute, $this->activeLocale, $data[$attribute]);
            }
        }

        foreach ($this->otherLocaleData as $locale => $localeData) {
            $existingTranslation = $record->getTranslation($translatableAttributes[0] ?? '', $locale, false);

            $hasExistingTranslations = filled($existingTranslation);
            $hasNewData = ! empty(array_filter($localeData, fn ($value) => filled($value)));

            if (! $hasExistingTranslations && ! $hasNewData) {
                continue;
            }

            foreach ($translatableAttributes as $attribute) {
                if (array_key_exists($attribute, $localeData)) {
                    $record->setTranslation($attribute, $locale, $localeData[$attribute]);
                }
            }
        }

        $record->save();

        return $record;
    }

    protected ?string $oldActiveLocale = null;

    public function updatingActiveLocale(): void
    {
        $this->oldActiveLocale = $this->activeLocale;
    }

    public function updatedActiveLocale(?string $newActiveLocale = null): void
    {
        if (blank($this->oldActiveLocale)) {
            return;
        }

        $translatableAttributes = static::getResource()::getTranslatableAttributes();

        $this->otherLocaleData[$this->oldActiveLocale] = collect($this->form->getState())
            ->only($translatableAttributes)
            ->all();

        $existingLocaleData = $this->otherLocaleData[$this->activeLocale] ?? [];

        $nonTranslatableData = collect($this->form->getState())
            ->except($translatableAttributes)
            ->all();

        $this->form->fill(array_merge($nonTranslatableData, $existingLocaleData));

        unset($this->otherLocaleData[$this->activeLocale]);

        $this->resetValidation();
    }

    public function setActiveLocale(string $locale): void
    {
        $this->updatingActiveLocale();
        $this->activeLocale = $locale;
        $this->updatedActiveLocale();
    }
}
