<?php

use JeffersonGoncalves\FilamentTranslatable\FilamentTranslatablePlugin;

it('has correct plugin id', function () {
    $plugin = FilamentTranslatablePlugin::make();

    expect($plugin->getId())->toBe('filament-translatable');
});

it('can set and get default locales', function () {
    $plugin = FilamentTranslatablePlugin::make()
        ->defaultLocales(['en', 'pt_BR', 'fr']);

    expect($plugin->getDefaultLocales())->toBe(['en', 'pt_BR', 'fr']);
});

it('can set locales to empty array', function () {
    $plugin = FilamentTranslatablePlugin::make()
        ->defaultLocales(null);

    expect($plugin->getDefaultLocales())->toBe([]);
});

it('can use custom locale label callback', function () {
    $plugin = FilamentTranslatablePlugin::make()
        ->defaultLocales(['en', 'pt_BR'])
        ->flagDisplay('label_only')
        ->getLocaleLabelUsing(fn (string $locale) => match ($locale) {
            'en' => 'English',
            'pt_BR' => 'Portugues',
            default => null,
        });

    expect($plugin->getLocaleLabel('en'))->toBe('English')
        ->and($plugin->getLocaleLabel('pt_BR'))->toBe('Portugues');
});

it('can configure locale flags', function () {
    $plugin = FilamentTranslatablePlugin::make()
        ->localeFlags([
            'en' => '🇺🇸',
            'pt_BR' => '🇧🇷',
        ]);

    expect($plugin->getLocaleFlag('en'))->toBe('🇺🇸')
        ->and($plugin->getLocaleFlag('pt_BR'))->toBe('🇧🇷');
});

it('falls back to config flags when plugin flags not set', function () {
    $plugin = FilamentTranslatablePlugin::make();

    $flag = $plugin->getLocaleFlag('en');

    expect($flag)->not->toBeNull();
});

it('can set flag display mode', function () {
    $plugin = FilamentTranslatablePlugin::make()
        ->flagDisplay('flag_only');

    expect($plugin->getFlagDisplay())->toBe('flag_only');
});

it('defaults flag display to config value', function () {
    $plugin = FilamentTranslatablePlugin::make();

    expect($plugin->getFlagDisplay())->toBe('flag_and_label');
});

it('returns label only when flag display is label_only', function () {
    $plugin = FilamentTranslatablePlugin::make()
        ->flagDisplay('label_only')
        ->getLocaleLabelUsing(fn (string $locale) => 'Test Label');

    $label = $plugin->getLocaleLabel('en');

    expect($label)->toBe('Test Label');
});

it('returns flag only when flag display is flag_only', function () {
    $plugin = FilamentTranslatablePlugin::make()
        ->flagDisplay('flag_only')
        ->localeFlags(['en' => '🇺🇸']);

    $label = $plugin->getLocaleLabel('en');

    expect($label)->toBe('🇺🇸');
});

it('returns flag and label when flag display is flag_and_label', function () {
    $plugin = FilamentTranslatablePlugin::make()
        ->flagDisplay('flag_and_label')
        ->localeFlags(['en' => '🇺🇸'])
        ->getLocaleLabelUsing(fn (string $locale) => 'English');

    $label = $plugin->getLocaleLabel('en');

    expect($label)->toBe('🇺🇸 English');
});

it('can get status colors from config', function () {
    $plugin = FilamentTranslatablePlugin::make();

    expect($plugin->getStatusColor('complete'))->toBe('success')
        ->and($plugin->getStatusColor('partial'))->toBe('warning')
        ->and($plugin->getStatusColor('empty'))->toBe('danger')
        ->and($plugin->getStatusColor('unknown'))->toBe('gray');
});
