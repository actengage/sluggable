<?php

namespace Tests;

use Actengage\Sluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string|null $title
 * @property string|null $slug
 */
class Page extends Model
{
    use Sluggable;

    protected $table = 'pages';

    /** @var list<string> */
    protected $fillable = [
        'title', 'slug',
    ];
}
