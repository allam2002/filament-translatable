<?php

namespace JeffersonGoncalves\FilamentTranslatable\Tests\Fixtures\Resources\PostResource\Pages;

use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentTranslatable\Actions\LocaleSwitcher;
use JeffersonGoncalves\FilamentTranslatable\Tests\Fixtures\Resources\PostResource;

class ListPosts extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
