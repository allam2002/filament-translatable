# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - YYYY-MM-DD

### Added

- Initial release for Filament v3
- Plugin class with `defaultLocales()` and `getLocaleLabelUsing()` configuration
- Content driver bridging Filament forms/tables with Spatie translatable models
- LocaleSwitcher actions for pages and tables
- Translatable traits for all page types (Create, Edit, List, Manage, View)
- RelationManager translatable support
- Translation status indicator (`HasTranslationStatus` trait)
- `TranslationStatusColumn` table column with colored badges per locale
- Locale flags support with emoji flag mapping
- `InteractsWithTranslations` unified trait for less boilerplate
- SQLite JSON search support
- Laravel Boost integration (guidelines + skill)

[Unreleased]: https://github.com/jeffersongoncalves/filament-translatable/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/jeffersongoncalves/filament-translatable/releases/tag/v1.0.0
