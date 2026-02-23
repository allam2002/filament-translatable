<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Locale Flags
    |--------------------------------------------------------------------------
    |
    | Map of locale codes to emoji flags. Used by the LocaleSwitcher and
    | TranslationStatusColumn to display visual locale indicators.
    |
    */

    'locale_flags' => [
        'en' => "\u{1F1FA}\u{1F1F8}",
        'pt_BR' => "\u{1F1E7}\u{1F1F7}",
        'pt' => "\u{1F1F5}\u{1F1F9}",
        'es' => "\u{1F1EA}\u{1F1F8}",
        'fr' => "\u{1F1EB}\u{1F1F7}",
        'de' => "\u{1F1E9}\u{1F1EA}",
        'it' => "\u{1F1EE}\u{1F1F9}",
        'nl' => "\u{1F1F3}\u{1F1F1}",
        'ja' => "\u{1F1EF}\u{1F1F5}",
        'ko' => "\u{1F1F0}\u{1F1F7}",
        'zh' => "\u{1F1E8}\u{1F1F3}",
        'ru' => "\u{1F1F7}\u{1F1FA}",
        'ar' => "\u{1F1F8}\u{1F1E6}",
        'hi' => "\u{1F1EE}\u{1F1F3}",
        'tr' => "\u{1F1F9}\u{1F1F7}",
        'pl' => "\u{1F1F5}\u{1F1F1}",
        'uk' => "\u{1F1FA}\u{1F1E6}",
        'sv' => "\u{1F1F8}\u{1F1EA}",
        'da' => "\u{1F1E9}\u{1F1F0}",
        'no' => "\u{1F1F3}\u{1F1F4}",
        'fi' => "\u{1F1EB}\u{1F1EE}",
        'cs' => "\u{1F1E8}\u{1F1FF}",
        'el' => "\u{1F1EC}\u{1F1F7}",
        'ro' => "\u{1F1F7}\u{1F1F4}",
        'hu' => "\u{1F1ED}\u{1F1FA}",
        'th' => "\u{1F1F9}\u{1F1ED}",
        'vi' => "\u{1F1FB}\u{1F1F3}",
        'id' => "\u{1F1EE}\u{1F1E9}",
        'ms' => "\u{1F1F2}\u{1F1FE}",
        'he' => "\u{1F1EE}\u{1F1F1}",
    ],

    /*
    |--------------------------------------------------------------------------
    | Flag Display Format
    |--------------------------------------------------------------------------
    |
    | Controls how locale labels are displayed in the LocaleSwitcher.
    |
    | Options: 'flag_and_label', 'flag_only', 'label_only'
    |
    */

    'flag_display' => 'flag_and_label',

    /*
    |--------------------------------------------------------------------------
    | Translation Status Colors
    |--------------------------------------------------------------------------
    |
    | Filament color names used by the TranslationStatusColumn badges.
    |
    */

    'status_colors' => [
        'complete' => 'success',
        'partial' => 'warning',
        'empty' => 'danger',
    ],

];
