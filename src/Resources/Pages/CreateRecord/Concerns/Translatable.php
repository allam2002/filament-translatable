<?php

namespace JeffersonGoncalves\FilamentTranslatable\Resources\Pages\CreateRecord\Concerns;

use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\FilamentTranslatable\Resources\Concerns\HasActiveLocaleSwitcher;
use Livewire\Attributes\Locked;

trait Translatable
{
    use HasActiveLocaleSwitcher;

    protected ?string $oldActiveLocale = null;

    #[Locked]
    public array $otherLocaleData = [];

    public function mountTranslatable(): void
    {
        $this->activeLocale = static::getResource()::getDefaultTranslatableLocale();
    }

    public function getTranslatableLocales(): array
    {
        return static::getResource()::getTranslatableLocales();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $record = app(static::getModel());

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
            if (empty(array_filter($localeData, fn ($value) => filled($value)))) {
                continue;
            }

            foreach ($translatableAttributes as $attribute) {
                if (array_key_exists($attribute, $localeData)) {
                    $record->setTranslation($attribute, $locale, $localeData[$attribute]);
                }
            }
        }

        if (
            static::getResource()::isScopedToTenant() &&
            ($tenant = filament()->getTenant())
        ) {
            return $this->associateRecordWithTenant($record, $tenant);
        }

        $record->save();

        return $record;
    }

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
}
