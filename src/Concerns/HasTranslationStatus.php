<?php

namespace JeffersonGoncalves\FilamentTranslatable\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasTranslationStatus
{
    /**
     * Get translation status for each configured locale.
     *
     * @return array<string, string> Map of locale => status ('complete', 'partial', 'empty')
     */
    public function getTranslationStatus(?Model $record = null): array
    {
        $record = $record ?? ($this->getRecord() ?? null);

        if (! $record || ! method_exists($record, 'getTranslatableAttributes')) {
            return [];
        }

        $translatableAttributes = $record->getTranslatableAttributes();
        $locales = $this->getTranslatableLocalesForStatus();
        $status = [];

        foreach ($locales as $locale) {
            $filledCount = 0;

            foreach ($translatableAttributes as $attribute) {
                $value = $record->getTranslation($attribute, $locale, false);

                if (filled($value)) {
                    $filledCount++;
                }
            }

            if ($filledCount === 0) {
                $status[$locale] = 'empty';
            } elseif ($filledCount === count($translatableAttributes)) {
                $status[$locale] = 'complete';
            } else {
                $status[$locale] = 'partial';
            }
        }

        return $status;
    }

    /**
     * Get translation completeness percentage for each locale.
     *
     * @return array<string, int> Map of locale => percentage (0-100)
     */
    public function getTranslationCompleteness(?Model $record = null): array
    {
        $record = $record ?? ($this->getRecord() ?? null);

        if (! $record || ! method_exists($record, 'getTranslatableAttributes')) {
            return [];
        }

        $translatableAttributes = $record->getTranslatableAttributes();
        $locales = $this->getTranslatableLocalesForStatus();
        $totalAttributes = count($translatableAttributes);
        $completeness = [];

        if ($totalAttributes === 0) {
            return [];
        }

        foreach ($locales as $locale) {
            $filledCount = 0;

            foreach ($translatableAttributes as $attribute) {
                $value = $record->getTranslation($attribute, $locale, false);

                if (filled($value)) {
                    $filledCount++;
                }
            }

            $completeness[$locale] = (int) round(($filledCount / $totalAttributes) * 100);
        }

        return $completeness;
    }

    /**
     * Get locales that have at least one translated attribute.
     *
     * @return array<string>
     */
    public function getTranslatedLocales(?Model $record = null): array
    {
        $status = $this->getTranslationStatus($record);

        return array_keys(array_filter($status, fn (string $s) => $s !== 'empty'));
    }

    /**
     * Get the locales to check status for.
     *
     * @return array<string>
     */
    protected function getTranslatableLocalesForStatus(): array
    {
        if (method_exists($this, 'getTranslatableLocales')) {
            return $this->getTranslatableLocales();
        }

        /** @var \JeffersonGoncalves\FilamentTranslatable\FilamentTranslatablePlugin $plugin */
        $plugin = filament('filament-translatable');

        return $plugin->getDefaultLocales();
    }
}
