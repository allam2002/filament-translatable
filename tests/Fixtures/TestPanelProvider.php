<?php

namespace JeffersonGoncalves\FilamentTranslatable\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use JeffersonGoncalves\FilamentTranslatable\FilamentTranslatablePlugin;

class TestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->plugins([
                FilamentTranslatablePlugin::make()
                    ->defaultLocales(['en', 'pt_BR', 'fr']),
            ]);
    }
}
