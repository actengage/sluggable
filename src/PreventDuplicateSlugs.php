<?php

namespace Actengage\Sluggable;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class PreventDuplicateSlugs
{
    public function __construct(
        public bool $enabled = true,
    ) {}
}
