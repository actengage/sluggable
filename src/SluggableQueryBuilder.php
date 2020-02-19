<?php

namespace Actengage\Sluggable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Arrayable;

class SluggableQueryBuilder extends Builder
{
    public function whereKey($key)
    {
        if(is_numeric($key)) {
            return parent::whereKey($key);
        }
        
        if($key instanceof Arrayable) {
            $key = $key->toArray();
        }

        collect($key)->each(function($key) {
            $this->whereSlugOrKey($key);
        });
        
        return $this;
    }

    public function whereSlugOrKey($key)
    {
        return $this->where(function($query) use ($key) {
            if(is_numeric($key)) {
                $query->orWhere($this->model->getQualifiedKeyName(), '=', $key);
            }
            else {
                $query->orWhere($this->model->getSlugAttributeName(), '=', $this->model->slugify($key));
            }
        });
    }

}