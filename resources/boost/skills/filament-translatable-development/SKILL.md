---
name: filament-translatable-development
description: Build and work with Filament Translatable plugin features, including locale switching, translation status, and translatable resources.
---

# Filament Translatable Development

## When to use this skill

Use this skill when:
- Integrating Filament Translatable plugin into a panel
- Adding locale switching to Filament resources and pages
- Using TranslationStatusColumn in tables
- Checking translation completeness programmatically
- Configuring locale flags and labels
- Migrating from filament/spatie-laravel-translatable-plugin

## Configuration

### Basic Setup

```php
use JeffersonGoncalves\FilamentTranslatable\FilamentTranslatablePlugin;

FilamentTranslatablePlugin::make()
    ->defaultLocales(['en', 'pt_BR', 'es'])
    ->localeFlags(['en' => '🇺🇸', 'pt_BR' => '🇧🇷', 'es' => '🇪🇸'])
    ->flagDisplay('flag_and_label')
    ->getLocaleLabelUsing(fn (string $locale) => match ($locale) {
        'en' => 'English',
        'pt_BR' => 'Portugues',
        'es' => 'Espanol',
        default => null,
    });
```

### Model Requirements

The model must use Spatie's HasTranslations trait:

```php
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'content'];
}
```

## Resource Setup

```php
use JeffersonGoncalves\FilamentTranslatable\Resources\Concerns\Translatable;

class PostResource extends Resource
{
    use Translatable;
}
```

## Page Traits

Each page type has its own Translatable trait:

- `CreateRecord\Concerns\Translatable`
- `EditRecord\Concerns\Translatable`
- `ListRecords\Concerns\Translatable`
- `ManageRecords\Concerns\Translatable`
- `ViewRecord\Concerns\Translatable`

Always add `LocaleSwitcher::make()` to `getHeaderActions()`.

## Enhanced Features

### TranslationStatusColumn

```php
use JeffersonGoncalves\FilamentTranslatable\Tables\Columns\TranslationStatusColumn;

TranslationStatusColumn::make('translations')
    ->showPercentage()
    ->onlyShowMissing()
    ->showFlags()
    ->locales(['en', 'pt_BR'])
```

### HasTranslationStatus Trait

```php
use JeffersonGoncalves\FilamentTranslatable\Concerns\HasTranslationStatus;

// Methods available:
$this->getTranslationStatus($record);       // ['en' => 'complete', 'fr' => 'empty']
$this->getTranslationCompleteness($record);  // ['en' => 100, 'fr' => 0]
$this->getTranslatedLocales($record);         // ['en', 'pt_BR']
```

## Troubleshooting

### Locale switcher not showing
**Cause**: Trait or LocaleSwitcher not added to page
**Solution**: Add both the page-specific Translatable trait AND `LocaleSwitcher::make()` to header actions

### Translations not saving
**Cause**: Database columns not using `json` type
**Solution**: Ensure translatable columns are `json` in migration

### Search not working
**Cause**: Content driver not set
**Solution**: Ensure the Resource uses `Translatable` trait which sets the content driver
