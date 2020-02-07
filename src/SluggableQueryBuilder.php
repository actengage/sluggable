<?php

namespace Actengage\Sluggable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Arrayable;

class SluggableQueryBuilder extends Builder
{
    public function whereKey($id)
    {
        if(is_numeric($id)) {
            return parent::whereKey($id);
        }

        $ids = collect($id instanceof Arrayable ? $id->toArray() : $id);

        $this->query->where(function($query) use ($ids) {
            $slugs = $ids->map(function($id) {
                return $this->model->slugify($id);
            });

            $query->orWhereIn($this->model->getKeyName(), $ids->unique());
            $query->orWhereIn($this->model->getSlugAttributeName(), $slugs->unique());
        });

        return $this;
    }

}