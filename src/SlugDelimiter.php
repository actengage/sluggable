<?php

namespace Actengage\Sluggable;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class SlugDelimiter
{
    public function __construct(
        public string $delimiter = '-',
    ) {}
}
