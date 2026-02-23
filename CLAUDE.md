# Filament Translatable Plugin

## Project Info

- **Package**: `jeffersongoncalves/filament-translatable`
- **Namespace**: `JeffersonGoncalves\FilamentTranslatable`
- **Plugin ID**: `filament-translatable`
- **Branch**: `1.x` (Filament v3)
- **PHP**: ^8.1
- **Filament**: ^3.0
- **Spatie Translatable**: ^6.0

## Branch Strategy

| Branch | Filament | PHP | Laravel | Tailwind | Livewire |
|--------|----------|-----|---------|----------|----------|
| 1.x | v3 | ^8.1 | 10+ | 3.x | 3.x |
| 2.x | v4 | ^8.2 | 11+ | 4.x | 3.x |
| 3.x | v5 | ^8.2 | 11.28+ | 4.x | 4.x |

## Commands

```bash
# Install dependencies
"/c/Users/simao/.config/herd/bin/composer.bat" install

# Run tests
"/c/Users/simao/.config/herd/bin/php.bat" vendor/bin/pest

# PHPStan analysis
"/c/Users/simao/.config/herd/bin/php.bat" vendor/bin/phpstan analyse

# Code formatting
"/c/Users/simao/.config/herd/bin/php.bat" vendor/bin/pint
```

## Architecture

Enhanced fork of `filament/spatie-laravel-translatable-plugin` with:
- `TranslationStatusColumn` — table column showing translation completeness badges
- `HasTranslationStatus` — trait for translation status introspection
- `InteractsWithTranslations` — unified trait (less boilerplate)
- Locale flags support (emoji)
- SQLite JSON search support
- Config file for flags, display format, status colors

## Key Files

- `src/FilamentTranslatablePlugin.php` — Plugin class (config, locales, flags)
- `src/FilamentTranslatableContentDriver.php` — Bridge Filament <-> Spatie
- `src/FilamentTranslatableServiceProvider.php` — Package service provider
- `src/Resources/Concerns/HasActiveLocaleSwitcher.php` — Core locale state
- `src/Concerns/HasTranslationStatus.php` — Translation completeness
- `src/Tables/Columns/TranslationStatusColumn.php` — Visual status column
