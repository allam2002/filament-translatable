<?php

namespace JeffersonGoncalves\FilamentTranslatable\Tests\Fixtures\Resources\PostResource\Pages;

use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentTranslatable\Actions\LocaleSwitcher;
use JeffersonGoncalves\FilamentTranslatable\Tests\Fixtures\Resources\PostResource;

class EditPost extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
