<?php

namespace Actengage\Sluggable;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Slug
{
    public function __construct(
        public string $qualifier = 'title',
    ) {}
}
