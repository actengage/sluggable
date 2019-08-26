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
        
        if($id instanceof Arrayable) {
            $id = $id->toArray();
        }

        if(is_array($id)) {
            $values = array_map(function($id) {
                return $this->model->slugify($id);
            }, $id);

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