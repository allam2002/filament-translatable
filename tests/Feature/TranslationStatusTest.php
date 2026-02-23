<?php

use JeffersonGoncalves\FilamentTranslatable\Concerns\HasTranslationStatus;
use JeffersonGoncalves\FilamentTranslatable\Tests\Fixtures\Models\Post;

// Create an anonymous class that uses the trait for testing
beforeEach(function () {
    $this->statusChecker = new class
    {
        use HasTranslationStatus;

        public function getTranslatableLocales(): array
        {
            return ['en', 'pt_BR', 'fr'];
        }

        public function getRecord(): ?Post
        {
            return null;
        }
    };
});

it('returns complete status when all attributes are translated', function () {
    $post = Post::create([
        'title' => ['en' => 'Title', 'pt_BR' => 'Titulo', 'fr' => 'Titre'],
        'content' => ['en' => 'Content', 'pt_BR' => 'Conteudo', 'fr' => 'Contenu'],
        'slug' => 'test',
    ]);

    $status = $this->statusChecker->getTranslationStatus($post);

    expect($status['en'])->toBe('complete')
        ->and($status['pt_BR'])->toBe('complete')
        ->and($status['fr'])->toBe('complete');
});

it('returns partial status when some attributes are translated', function () {
    $post = Post::create([
        'title' => ['en' => 'Title', 'pt_BR' => 'Titulo'],
        'content' => ['en' => 'Content'],
        'slug' => 'test',
    ]);

    $status = $this->statusChecker->getTranslationStatus($post);

    expect($status['en'])->toBe('complete')
        ->and($status['pt_BR'])->toBe('partial')
        ->and($status['fr'])->toBe('empty');
});

it('returns empty status when no attributes are translated', function () {
    $post = Post::create([
        'title' => ['en' => 'Title'],
        'slug' => 'test',
    ]);

    $status = $this->statusChecker->getTranslationStatus($post);

    expect($status['fr'])->toBe('empty');
});

it('calculates completeness percentage', function () {
    $post = Post::create([
        'title' => ['en' => 'Title', 'pt_BR' => 'Titulo'],
        'content' => ['en' => 'Content'],
        'slug' => 'test',
    ]);

    $completeness = $this->statusChecker->getTranslationCompleteness($post);

    expect($completeness['en'])->toBe(100)
        ->and($completeness['pt_BR'])->toBe(50)
        ->and($completeness['fr'])->toBe(0);
});

it('returns translated locales', function () {
    $post = Post::create([
        'title' => ['en' => 'Title', 'pt_BR' => 'Titulo'],
        'content' => ['en' => 'Content'],
        'slug' => 'test',
    ]);

    $translatedLocales = $this->statusChecker->getTranslatedLocales($post);

    expect($translatedLocales)->toBe(['en', 'pt_BR']);
});

it('returns empty array for non-translatable model', function () {
    $status = $this->statusChecker->getTranslationStatus(null);

    expect($status)->toBe([]);
});
