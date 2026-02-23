# Filament Translatable

Enhanced Filament plugin for [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable) with translation status indicators, locale flags, and improved developer experience.

## Features

- **Locale Switching** — Switch between locales on Create, Edit, List, View, and Manage pages
- **Translation Status Column** — Visual table column showing translation completeness per locale with colored badges
- **Translation Status Trait** — Introspect translation status (complete/partial/empty) and completeness percentage
- **Locale Flags** — Emoji flag support with configurable display (flag+label, flag only, label only)
- **Unified DX Trait** — `InteractsWithTranslations` for less boilerplate
- **SQLite Search** — JSON search support for SQLite (in addition to MySQL and PostgreSQL)
- **RelationManager Support** — Independent locale management for relation managers

## Requirements

| Dependency | Version |
|------------|---------|
| PHP | ^8.1 |
| Laravel | ^10.0 |
| Filament | ^3.0 |
| spatie/laravel-translatable | ^6.0 |

## Installation

```bash
composer require jeffersongoncalves/filament-translatable:"^1.0"
```

Optionally publish the config:

```bash
php artisan vendor:publish --tag="filament-translatable-config"
```

## Setup

### 1. Configure Model

Your model must use Spatie's `HasTranslations` trait:

```php
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasTranslations;

    protected $fillable = ['title', 'content', 'slug'];

    public array $translatable = ['title', 'content'];
}
```

Translatable columns must be `json` in your migration:

```php
$table->json('title');
$table->json('content');
$table->string('slug'); // non-translatable
```

### 2. Register Plugin

```php
use JeffersonGoncalves\FilamentTranslatable\FilamentTranslatablePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentTranslatablePlugin::make()
                ->defaultLocales(['en', 'pt_BR', 'es']),
        ]);
}
```

### 3. Add Translatable to Resource

```php
use JeffersonGoncalves\FilamentTranslatable\Resources\Concerns\Translatable;

class PostResource extends Resource
{
    use Translatable;
    // ...
}
```

### 4. Add Translatable to Pages

Each page needs its own trait and the `LocaleSwitcher` action:

```php
use JeffersonGoncalves\FilamentTranslatable\Actions\LocaleSwitcher;

// CreateRecord
class CreatePost extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [LocaleSwitcher::make()];
    }
}

// EditRecord
class EditPost extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [LocaleSwitcher::make()];
    }
}

// ListRecords
class ListPosts extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [LocaleSwitcher::make()];
    }
}
```

## Enhanced Features

### Translation Status Column

Show translation completeness per locale in your table:

```php
use JeffersonGoncalves\FilamentTranslatable\Tables\Columns\TranslationStatusColumn;

public static function table(Table $table): Table
{
    return $table->columns([
        TextColumn::make('title'),
        TranslationStatusColumn::make('translations')
            ->showPercentage()    // show completion %
            ->onlyShowMissing()   // hide complete locales
            ->showFlags()         // show emoji flags
            ->locales(['en', 'pt_BR', 'es']), // custom locales
    ]);
}
```

### Translation Status Trait

Use `HasTranslationStatus` to check translation status programmatically:

```php
use JeffersonGoncalves\FilamentTranslatable\Concerns\HasTranslationStatus;

class EditPost extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    use HasTranslationStatus;

    // Available methods:
    // $this->getTranslationStatus($record)       => ['en' => 'complete', 'pt_BR' => 'partial', 'fr' => 'empty']
    // $this->getTranslationCompleteness($record)  => ['en' => 100, 'pt_BR' => 50, 'fr' => 0]
    // $this->getTranslatedLocales($record)         => ['en', 'pt_BR']
}
```

### Locale Flags

Configure emoji flags per locale:

```php
FilamentTranslatablePlugin::make()
    ->defaultLocales(['en', 'pt_BR', 'es'])
    ->localeFlags([
        'en' => '🇺🇸',
        'pt_BR' => '🇧🇷',
        'es' => '🇪🇸',
    ])
    ->flagDisplay('flag_and_label'), // 'flag_and_label', 'flag_only', 'label_only'
```

### Custom Locale Labels

```php
FilamentTranslatablePlugin::make()
    ->defaultLocales(['en', 'pt_BR'])
    ->getLocaleLabelUsing(fn (string $locale) => match ($locale) {
        'en' => 'English',
        'pt_BR' => 'Portugues',
        default => null,
    }),
```

### RelationManager

```php
use JeffersonGoncalves\FilamentTranslatable\Tables\Actions\LocaleSwitcher;

class CommentsRelationManager extends RelationManager
{
    use RelationManager\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [LocaleSwitcher::make()];
    }
}
```

## Configuration

```php
// config/filament-translatable.php

return [
    // Locale to emoji flag mapping
    'locale_flags' => [
        'en' => '🇺🇸',
        'pt_BR' => '🇧🇷',
        // ...
    ],

    // Display format: 'flag_and_label', 'flag_only', 'label_only'
    'flag_display' => 'flag_and_label',

    // Badge colors for TranslationStatusColumn
    'status_colors' => [
        'complete' => 'success',
        'partial' => 'warning',
        'empty' => 'danger',
    ],
];
```

## Migration from `filament/spatie-laravel-translatable-plugin`

1. Replace the package:
   ```bash
   composer remove filament/spatie-laravel-translatable-plugin
   composer require jeffersongoncalves/filament-translatable:"^1.0"
   ```

2. Update imports in your PanelProvider:
   ```php
   // Before
   use Filament\SpatieLaravelTranslatablePlugin;
   // After
   use JeffersonGoncalves\FilamentTranslatable\FilamentTranslatablePlugin;
   ```

3. Update imports in Resources and Pages — replace `Filament\Resources\` with `JeffersonGoncalves\FilamentTranslatable\Resources\`

4. Update `LocaleSwitcher` imports — replace `Filament\Actions\LocaleSwitcher` with `JeffersonGoncalves\FilamentTranslatable\Actions\LocaleSwitcher`

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
