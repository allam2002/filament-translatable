<?php

namespace JeffersonGoncalves\FilamentTranslatable\Tests\Fixtures\Resources\PostResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentTranslatable\Actions\LocaleSwitcher;
use JeffersonGoncalves\FilamentTranslatable\Tests\Fixtures\Resources\PostResource;

class CreatePost extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
