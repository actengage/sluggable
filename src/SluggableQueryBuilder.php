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
            $values = array_map($id, function($id) {
                return $this->model->slugify($id);
            });

            array_push($values, $id);

            $this->query->whereIn($this->model->getSlugAttributeName(), array_unique($values));

            return $this;
        }

        return $this->where(function($query) use ($id) {
            $query->orWhere($this->model->getSlugAttributeName(), '=', $id);
            $query->orWhere($this->model->getSlugAttributeName(), '=', $this->model->slugify($id));
        });
    }

}