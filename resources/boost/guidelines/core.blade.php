## Filament Translatable Plugin

Enhanced Filament plugin for spatie/laravel-translatable with translation status indicators, locale flags, and improved DX.

### Installation

@verbatim
<code-snippet name="Install the plugin" lang="bash">
composer require jeffersongoncalves/filament-translatable:"^1.0"
</code-snippet>
@endverbatim

### Register Plugin

@verbatim
<code-snippet name="Register in PanelProvider" lang="php">
use JeffersonGoncalves\FilamentTranslatable\FilamentTranslatablePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentTranslatablePlugin::make()
                ->defaultLocales(['en', 'pt_BR', 'es'])
                ->localeFlags(['en' => '🇺🇸', 'pt_BR' => '🇧🇷', 'es' => '🇪🇸']),
        ]);
}
</code-snippet>
@endverbatim

### Resource Setup

@verbatim
<code-snippet name="Add Translatable trait to Resource" lang="php">
use JeffersonGoncalves\FilamentTranslatable\Resources\Concerns\Translatable;

class PostResource extends Resource
{
    use Translatable;
}
</code-snippet>
@endverbatim

### Page Setup

Add the correct Translatable trait + LocaleSwitcher to each page:

@verbatim
<code-snippet name="EditRecord with Translatable" lang="php">
use JeffersonGoncalves\FilamentTranslatable\Actions\LocaleSwitcher;

class EditPost extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [LocaleSwitcher::make()];
    }
}
</code-snippet>
@endverbatim

### Translation Status Column

@verbatim
<code-snippet name="TranslationStatusColumn in table" lang="php">
use JeffersonGoncalves\FilamentTranslatable\Tables\Columns\TranslationStatusColumn;

TranslationStatusColumn::make('translations')
    ->showPercentage()
    ->onlyShowMissing()
    ->showFlags()
</code-snippet>
@endverbatim

### Best Practices

- Always add `LocaleSwitcher::make()` to every page's header actions
- Translatable columns must be `json` type in migrations
- The model must use `Spatie\Translatable\HasTranslations` trait
- RelationManagers manage locales independently from the parent resource
