<?php

namespace JeffersonGoncalves\FilamentTranslatable\Tables\Columns;

use Filament\Tables\Columns\Column;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\FilamentTranslatable\FilamentTranslatablePlugin;

class TranslationStatusColumn extends Column
{
    protected string $view = 'filament-translatable::tables.columns.translation-status-column';

    protected ?array $locales = null;

    protected bool $onlyShowMissing = false;

    protected bool $showPercentage = false;

    protected bool $showFlags = true;

    public function locales(array $locales): static
    {
        $this->locales = $locales;

        return $this;
    }

    public function onlyShowMissing(bool $condition = true): static
    {
        $this->onlyShowMissing = $condition;

        return $this;
    }

    public function showPercentage(bool $condition = true): static
    {
        $this->showPercentage = $condition;

        return $this;
    }

    public function showFlags(bool $condition = true): static
    {
        $this->showFlags = $condition;

        return $this;
    }

    public function getLocales(): array
    {
        if ($this->locales !== null) {
            return $this->locales;
        }

        /** @var FilamentTranslatablePlugin $plugin */
        $plugin = filament('filament-translatable');

        return $plugin->getDefaultLocales();
    }

    public function getOnlyShowMissing(): bool
    {
        return $this->onlyShowMissing;
    }

    public function getShowPercentage(): bool
    {
        return $this->showPercentage;
    }

    public function getShowFlags(): bool
    {
        return $this->showFlags;
    }

    /**
     * Get translation status for a record.
     *
     * @return array<string, array{status: string, percentage: int, label: string, flag: ?string, color: string}>
     */
    public function getTranslationStatusForRecord(Model $record): array
    {
        if (! method_exists($record, 'getTranslatableAttributes')) {
            return [];
        }

        /** @var FilamentTranslatablePlugin $plugin */
        $plugin = filament('filament-translatable');

        $translatableAttributes = $record->getTranslatableAttributes();
        $totalAttributes = count($translatableAttributes);
        $locales = $this->getLocales();
        $result = [];

        foreach ($locales as $locale) {
            $filledCount = 0;

            foreach ($translatableAttributes as $attribute) {
                $value = $record->getTranslation($attribute, $locale, false);

                if (filled($value)) {
                    $filledCount++;
                }
            }

            $percentage = $totalAttributes > 0
                ? (int) round(($filledCount / $totalAttributes) * 100)
                : 0;

            if ($filledCount === 0) {
                $status = 'empty';
            } elseif ($filledCount === $totalAttributes) {
                $status = 'complete';
            } else {
                $status = 'partial';
            }

            if ($this->onlyShowMissing && $status === 'complete') {
                continue;
            }

            $result[$locale] = [
                'status' => $status,
                'percentage' => $percentage,
                'label' => $plugin->getLocaleLabel($locale) ?? $locale,
                'flag' => $this->showFlags ? $plugin->getLocaleFlag($locale) : null,
                'color' => $plugin->getStatusColor($status),
            ];
        }

        return $result;
    }
}
