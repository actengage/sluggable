<?php

namespace Tests;

use Actengage\Sluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use Sluggable;
    
    protected $table = 'pages';
    
    protected $fillable = [
        'title', 'slug'
    ];
    
}
