<?php

namespace JeffersonGoncalves\FilamentTranslatable\Resources\Pages\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasTranslatableRecord
{
    public function getRecord(): Model
    {
        $record = parent::getRecord();

        if (method_exists($record, 'setLocale') && $this->activeLocale) {
            $record->setLocale($this->activeLocale);
        }

        return $record;
    }
}
