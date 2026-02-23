<?php

namespace JeffersonGoncalves\FilamentTranslatable\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasTranslations;

    protected $fillable = ['title', 'content', 'slug'];

    public array $translatable = ['title', 'content'];
}
