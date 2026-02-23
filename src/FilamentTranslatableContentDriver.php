<?php

namespace JeffersonGoncalves\FilamentTranslatable;

use Filament\Support\Contracts\TranslatableContentDriver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FilamentTranslatableContentDriver implements TranslatableContentDriver
{
    public function __construct(
        protected string $activeLocale,
    ) {}

    public function isAttributeTranslatable(string $model, string $attribute): bool
    {
        if (! method_exists($model, 'isTranslatableAttribute')) {
            return false;
        }

        return app($model)->isTranslatableAttribute($attribute);
    }

    public function makeRecord(string $model, array $data): Model
    {
        $record = new $model;

        if (method_exists($record, 'setLocale')) {
            $record->setLocale($this->activeLocale);
        }

        $translatableAttributes = $this->getTranslatableAttributes($record);

        $nonTranslatableData = array_diff_key($data, array_flip($translatableAttributes));
        $translatableData = array_intersect_key($data, array_flip($translatableAttributes));

        $record->fill($nonTranslatableData);

        foreach ($translatableData as $key => $value) {
            $record->setTranslation($key, $this->activeLocale, $value);
        }

        return $record;
    }

    public function setRecordLocale(Model $record): Model
    {
        if (method_exists($record, 'setLocale')) {
            $record->setLocale($this->activeLocale);
        }

        return $record;
    }

    public function updateRecord(Model $record, array $data): Model
    {
        if (method_exists($record, 'setLocale')) {
            $record->setLocale($this->activeLocale);
        }

        $translatableAttributes = $this->getTranslatableAttributes($record);

        $nonTranslatableData = array_diff_key($data, array_flip($translatableAttributes));
        $translatableData = array_intersect_key($data, array_flip($translatableAttributes));

        $record->fill($nonTranslatableData);

        foreach ($translatableData as $key => $value) {
            $record->setTranslation($key, $this->activeLocale, $value);
        }

        $record->save();

        return $record;
    }

    public function getRecordAttributesToArray(Model $record): array
    {
        $attributes = $record->attributesToArray();

        if (! method_exists($record, 'getTranslatableAttributes')) {
            return $attributes;
        }

        foreach ($record->getTranslatableAttributes() as $attribute) {
            if (array_key_exists($attribute, $attributes)) {
                $attributes[$attribute] = $record->getTranslation($attribute, $this->activeLocale);
            }
        }

        return $attributes;
    }

    public function applySearchConstraintToQuery(
        Builder $query,
        string $column,
        string $search,
        string $whereClause,
        ?bool $isCaseInsensitivityForced = null,
    ): Builder {
        $driver = $query->getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            $locale = str_replace("'", "''", $this->activeLocale);
            $expression = "{$column}->>'{$locale}'";
        } else {
            $locale = str_replace("'", "\\'", $this->activeLocale);
            $expression = "json_extract({$column}, '\$.{$locale}')";
        }

        $query->{$whereClause}(DB::raw($expression), 'like', "%{$search}%");

        return $query;
    }

    protected function getTranslatableAttributes(Model $record): array
    {
        if (! method_exists($record, 'getTranslatableAttributes')) {
            return [];
        }

        return $record->getTranslatableAttributes();
    }
}
