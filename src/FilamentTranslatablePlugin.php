<?php

namespace JeffersonGoncalves\FilamentTranslatable;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentTranslatablePlugin implements Plugin
{
    protected array $defaultLocales = [];

    protected ?Closure $getLocaleLabelUsing = null;

    protected array $localeFlags = [];

    protected ?string $flagDisplay = null;

    public function getId(): string
    {
        return 'filament-translatable';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function defaultLocales(?array $locales): static
    {
        $this->defaultLocales = $locales ?? [];

        return $this;
    }

    public function getDefaultLocales(): array
    {
        return $this->defaultLocales;
    }

    public function getLocaleLabelUsing(?Closure $callback): static
    {
        $this->getLocaleLabelUsing = $callback;

        return $this;
    }

    public function localeFlags(array $flags): static
    {
        $this->localeFlags = $flags;

        return $this;
    }

    public function flagDisplay(string $display): static
    {
        $this->flagDisplay = $display;

        return $this;
    }

    public function getFlagDisplay(): string
    {
        return $this->flagDisplay ?? config('filament-translatable.flag_display', 'flag_and_label');
    }

    public function getLocaleFlag(string $locale): ?string
    {
        if (! empty($this->localeFlags) && isset($this->localeFlags[$locale])) {
            return $this->localeFlags[$locale];
        }

        $flags = config('filament-translatable.locale_flags', []);

        return $flags[$locale] ?? null;
    }

    public function getLocaleLabel(string $locale, ?string $displayLocale = null): ?string
    {
        $label = null;
        $flag = null;

        if ($this->getLocaleLabelUsing) {
            $label = ($this->getLocaleLabelUsing)($locale);
        }

        if ($label === null) {
            $label = locale_get_display_name($locale, $displayLocale) ?: null;
        }

        $flagDisplay = $this->getFlagDisplay();

        if ($flagDisplay === 'label_only') {
            return $label;
        }

        $flag = $this->getLocaleFlag($locale);

        if ($flagDisplay === 'flag_only' && $flag !== null) {
            return $flag;
        }

        if ($flag !== null && $label !== null) {
            return "{$flag} {$label}";
        }

        return $label;
    }

    public function getStatusColor(string $status): string
    {
        $colors = config('filament-translatable.status_colors', []);

        return $colors[$status] ?? 'gray';
    }
}
