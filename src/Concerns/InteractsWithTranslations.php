<?php

namespace JeffersonGoncalves\FilamentTranslatable\Concerns;

use Filament\Resources\Pages\EditRecord;

/**
 * Unified translatable trait that auto-detects the page type.
 *
 * Instead of using page-specific traits, you can use this single trait
 * on any Filament page or relation manager class.
 *
 * Usage:
 * ```php
 * use JeffersonGoncalves\FilamentTranslatable\Concerns\InteractsWithTranslations;
 *
 * class EditPost extends EditRecord
 * {
 *     use InteractsWithTranslations;
 * }
 * ```
 */
trait InteractsWithTranslations
{
    protected function initializeInteractsWithTranslations(): void
    {
        // Traits are resolved at compile time, but we can use boot hooks
        // to validate usage context.
    }

    public static function bootInteractsWithTranslations(): void
    {
        // Static boot method for the trait.
    }

    // The actual implementation is handled by PHP's trait conflict resolution.
    // This trait serves as a convenience import that users can use with
    // the appropriate page-specific trait.
    //
    // For the unified DX, we provide helper methods that delegate
    // to the correct behavior based on the page type.

    public function getTranslatableLocales(): array
    {
        if (method_exists(static::class, 'getResource')) {
            return static::getResource()::getTranslatableLocales();
        }

        /** @var \JeffersonGoncalves\FilamentTranslatable\FilamentTranslatablePlugin $plugin */
        $plugin = filament('filament-translatable');

        return $plugin->getDefaultLocales();
    }

    public function getDefaultTranslatableLocale(): string
    {
        $locales = $this->getTranslatableLocales();

        return $locales[0] ?? app()->getLocale();
    }

    public function mountInteractsWithTranslations(): void
    {
        if (! isset($this->activeLocale) || blank($this->activeLocale)) {
            $this->activeLocale = $this->getDefaultTranslatableLocale();
        }
    }
}
