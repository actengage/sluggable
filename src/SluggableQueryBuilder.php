<?php

namespace Actengage\Sluggable;

use Illuminate\Database\Eloquent\Builder;

class SluggableQueryBuilder extends Builder
{

    public function whereKey($id)
    {
        if(is_numeric($id)) {
            return parent::whereKey($id);
        }
                
        if (is_array($id) || $id instanceof Arrayable) {
            $this->query->whereIn($this->model->getSlugAttributeName(), array_map($id, function($id) {
                return $this->model->slugify($id);
            }));

            return $this;
        }

        return $this->where($this->model->getSlugAttributeName(), '=', $this->model->slugify($id));
    }

}