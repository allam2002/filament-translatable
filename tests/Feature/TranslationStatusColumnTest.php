<?php

use JeffersonGoncalves\FilamentTranslatable\Tables\Columns\TranslationStatusColumn;
use JeffersonGoncalves\FilamentTranslatable\Tests\Fixtures\Models\Post;

it('can be instantiated', function () {
    $column = TranslationStatusColumn::make('translations');

    expect($column)->toBeInstanceOf(TranslationStatusColumn::class);
});

it('uses plugin locales by default', function () {
    $column = TranslationStatusColumn::make('translations');

    $locales = $column->getLocales();

    expect($locales)->toBe(['en', 'pt_BR', 'fr']);
});

it('can set custom locales', function () {
    $column = TranslationStatusColumn::make('translations')
        ->locales(['en', 'es']);

    expect($column->getLocales())->toBe(['en', 'es']);
});

it('can toggle only show missing', function () {
    $column = TranslationStatusColumn::make('translations')
        ->onlyShowMissing();

    expect($column->getOnlyShowMissing())->toBeTrue();
});

it('can toggle show percentage', function () {
    $column = TranslationStatusColumn::make('translations')
        ->showPercentage();

    expect($column->getShowPercentage())->toBeTrue();
});

it('can toggle show flags', function () {
    $column = TranslationStatusColumn::make('translations')
        ->showFlags(false);

    expect($column->getShowFlags())->toBeFalse();
});

it('gets translation status for a record', function () {
    $post = Post::create([
        'title' => ['en' => 'Title', 'pt_BR' => 'Titulo'],
        'content' => ['en' => 'Content'],
        'slug' => 'test',
    ]);

    $column = TranslationStatusColumn::make('translations');

    $statuses = $column->getTranslationStatusForRecord($post);

    expect($statuses)->toHaveKeys(['en', 'pt_BR', 'fr'])
        ->and($statuses['en']['status'])->toBe('complete')
        ->and($statuses['en']['percentage'])->toBe(100)
        ->and($statuses['pt_BR']['status'])->toBe('partial')
        ->and($statuses['pt_BR']['percentage'])->toBe(50)
        ->and($statuses['fr']['status'])->toBe('empty')
        ->and($statuses['fr']['percentage'])->toBe(0);
});

it('filters complete locales when only show missing', function () {
    $post = Post::create([
        'title' => ['en' => 'Title', 'pt_BR' => 'Titulo'],
        'content' => ['en' => 'Content'],
        'slug' => 'test',
    ]);

    $column = TranslationStatusColumn::make('translations')
        ->onlyShowMissing();

    $statuses = $column->getTranslationStatusForRecord($post);

    expect($statuses)->not->toHaveKey('en')
        ->and($statuses)->toHaveKeys(['pt_BR', 'fr']);
});
