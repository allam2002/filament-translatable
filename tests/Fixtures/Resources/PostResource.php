<?php

namespace JeffersonGoncalves\FilamentTranslatable\Tests\Fixtures\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use JeffersonGoncalves\FilamentTranslatable\Resources\Concerns\Translatable;
use JeffersonGoncalves\FilamentTranslatable\Tests\Fixtures\Models\Post;

class PostResource extends Resource
{
    use Translatable;

    protected static ?string $model = Post::class;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\Textarea::make('content'),
                Forms\Components\TextInput::make('slug'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('slug'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Resources\PostResource\Pages\ListPosts::route('/'),
            'create' => Resources\PostResource\Pages\CreatePost::route('/create'),
            'edit' => Resources\PostResource\Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
