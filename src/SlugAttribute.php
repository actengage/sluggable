<?php

namespace Actengage\Sluggable;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class SlugAttribute
{
    public function __construct(
        public string $name = 'slug',
    ) {}
}
