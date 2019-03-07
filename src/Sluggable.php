<?php

namespace Actengage\Sluggable;

use Illuminate\Database\Eloquent\Model;

trait Sluggable {

    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootSluggable()
    {
        static::saving(function(Model $model) {
            $model->ensureSlugExists($model->getTitle());
        });
    }

    /**
     * Ensure this instance has a slug value.
     *
     * @return void
     */
    public function ensureSlugExists($default)
    {
        if(!$this->getSlug()) {
            $this->setSlug($default);
        }
    }

    /**
     * Should prevent duplicate slugs or not.
     *
     * @return void
     */
    public function preventDuplicateSlugs(): bool
    {
        return property_exists($this, 'preventDuplicateSlugs') ? $this->preventDuplicateSlugs : true;
    }

    /**
     * Get the slug delimiting string.
     *
     * @return string
     */
    public function getSlugDelimiter(): string
    {
        return '-';
    }

    /**
     * Get the slug attribute name.
     *
     * @return string
     */
    public function getSlugAttributeName(): string
    {
        return 'slug';
    }

    /**
     * Get the name of the attribute used to qualify a slug.
     *
     * @return string
     */
    public function getSlugQualifierAttributeName(): string
    {
        return 'title';
    }

    /**
     * Get the slug attribute.
     *
     * @return mixed
     */
    public function getSlug(): ?string
    {
        return $this->{$this->getSlugAttributeName()};
    }

    /**
     * Get the slug attribute.
     *
     * @return void
     */
    public function setSlug($value)
    {
        $this->{$this->getSlugAttributeName()} = $value;
    }

    /**
     * Get the title attribute.
     *
     * @return moxed
     */
    public function getTitle(): ?string
    {
        return $this->{$this->getSlugQualifierAttributeName()};
    }

    /**
     * Set the title attribute.
     *
     * @return void
     */
    public function setTitle($value)
    {
        $this->{$this->getTitleAttributeName()} = $value;
    }

    /**
     * Get the url attribute.
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return url($this->getTable().'/'.$this->getSlug());
    }

    /**
     * Scope a query to find a slug.
     *
     * @return void
     */
    public function scopeSlug($query, $value)
    {
        $query->whereSlug($value);
    }

    /**
     * Set the slug attribute.
     *
     * @return void
     */
    public function setSlugAttribute($value)
    {
        $this->attributes[$this->getSlugAttributeName()] = $this->createSlug($value);
    }

    /**
     * Create a slug from a give string. Checks for duplicates until a unique
     * slug is created.
     *
     * @return string
     */
    public function createSlug($value): string
    {
        $slug = str_slug($value, $this->getSlugDelimiter());

        if($this->preventDuplicateSlugs()) {
            $totalDuplicates = 0;

            while(static::whereSlug($slug)->count()) {
                $slug = str_slug(preg_replace('/-\d+$/', '', $slug) . ' ' . ($totalDuplicates += 1));
            }
        }

        return $slug;
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new SluggableQueryBuilder($query);
    }

}
