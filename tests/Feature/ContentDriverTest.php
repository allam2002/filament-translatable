<?php

use JeffersonGoncalves\FilamentTranslatable\FilamentTranslatableContentDriver;
use JeffersonGoncalves\FilamentTranslatable\Tests\Fixtures\Models\Post;

it('detects translatable attributes', function () {
    $driver = new FilamentTranslatableContentDriver('en');

    expect($driver->isAttributeTranslatable(Post::class, 'title'))->toBeTrue()
        ->and($driver->isAttributeTranslatable(Post::class, 'content'))->toBeTrue()
        ->and($driver->isAttributeTranslatable(Post::class, 'slug'))->toBeFalse();
});

it('returns false for non-translatable model', function () {
    $driver = new FilamentTranslatableContentDriver('en');

    expect($driver->isAttributeTranslatable(\Illuminate\Database\Eloquent\Model::class, 'name'))->toBeFalse();
});

it('creates a record with translations', function () {
    $driver = new FilamentTranslatableContentDriver('en');

    $record = $driver->makeRecord(Post::class, [
        'title' => 'English Title',
        'content' => 'English Content',
        'slug' => 'english-title',
    ]);

    $record->save();

    expect($record->getTranslation('title', 'en'))->toBe('English Title')
        ->and($record->getTranslation('content', 'en'))->toBe('English Content')
        ->and($record->slug)->toBe('english-title');
});

it('sets locale on record', function () {
    $driver = new FilamentTranslatableContentDriver('pt_BR');

    $record = Post::create([
        'title' => ['en' => 'English', 'pt_BR' => 'Portugues'],
        'slug' => 'test',
    ]);

    $driver->setRecordLocale($record);

    expect($record->title)->toBe('Portugues');
});

it('updates a record with translations', function () {
    $record = Post::create([
        'title' => ['en' => 'Old English'],
        'content' => ['en' => 'Old Content'],
        'slug' => 'old-slug',
    ]);

    $driver = new FilamentTranslatableContentDriver('en');

    $driver->updateRecord($record, [
        'title' => 'New English Title',
        'content' => 'New Content',
        'slug' => 'new-slug',
    ]);

    $record->refresh();

    expect($record->getTranslation('title', 'en'))->toBe('New English Title')
        ->and($record->getTranslation('content', 'en'))->toBe('New Content')
        ->and($record->slug)->toBe('new-slug');
});

it('preserves other locale translations when updating', function () {
    $record = Post::create([
        'title' => ['en' => 'English', 'pt_BR' => 'Portugues'],
        'slug' => 'test',
    ]);

    $driver = new FilamentTranslatableContentDriver('en');

    $driver->updateRecord($record, [
        'title' => 'Updated English',
        'slug' => 'test',
    ]);

    $record->refresh();

    expect($record->getTranslation('title', 'en'))->toBe('Updated English')
        ->and($record->getTranslation('title', 'pt_BR'))->toBe('Portugues');
});

it('gets record attributes for active locale', function () {
    $record = Post::create([
        'title' => ['en' => 'English', 'pt_BR' => 'Portugues'],
        'content' => ['en' => 'English Content', 'pt_BR' => 'Conteudo'],
        'slug' => 'test',
    ]);

    $driver = new FilamentTranslatableContentDriver('pt_BR');

    $attributes = $driver->getRecordAttributesToArray($record);

    expect($attributes['title'])->toBe('Portugues')
        ->and($attributes['content'])->toBe('Conteudo')
        ->and($attributes['slug'])->toBe('test');
});

it('applies search constraint to query for sqlite', function () {
    Post::create([
        'title' => ['en' => 'Hello World', 'pt_BR' => 'Ola Mundo'],
        'slug' => 'test',
    ]);

    Post::create([
        'title' => ['en' => 'Another Post', 'pt_BR' => 'Outro Post'],
        'slug' => 'test2',
    ]);

    $driver = new FilamentTranslatableContentDriver('en');

    $query = Post::query();
    $driver->applySearchConstraintToQuery($query, 'title', 'Hello', 'where', null);

    expect($query->count())->toBe(1)
        ->and($query->first()->slug)->toBe('test');
});
