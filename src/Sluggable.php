<?php

namespace Actengage\Sluggable;

use Illuminate\Database\Eloquent\Model;

/**
 * The sluggable class provides convenience methods to any Eloquent model that
 * needs to have a URI slug associated with it.
 */
trait Sluggable
{
    /**
     * Ensure this instance has a slug value.
     *
     * @param string|null $title
     * @return void
     */
    public function ensureSlugExists(?string $title): void
    {
        if(!$this->getSlug()) {
            $this->setSlug($title);
        }
    }

    /**
     * Should prevent duplicate slugs or not.
     *
     * @return bool
     */
    public function preventDuplicateSlugs(): bool
    {
        return property_exists($this, 'preventDuplicateSlugs') 
            ? (bool) $this->preventDuplicateSlugs
            : true;
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
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->{$this->getSlugAttributeName()};
    }

    /**
     * Set the slug attribute using the title.
     *
     * @param string|null $value
     * @return void
     */
    public function setSlug(?string $title): void
    {
        $this->{$this->getSlugAttributeName()} = $title;
    }

    /**
     * Get the title.
     *
     * @return string|null
     */
    public function title(): ?string
    {
        return $this->{$this->getSlugQualifierAttributeName()};
    }

    /**
     * A getter for the url attribute.
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
     * @param [type] $query
     * @param string|null $slug
     * @return void
     */
    public function scopeSlug($query, ?string $slug): void
    {
        $query->whereSlug($slug);
    }

    /**
     * Set the slug attribute.
     *
     * @param String|null $title
     * @return void
     */
    public function setSlugAttribute(?String $title): void
    {
        $this->attributes[$this->getSlugAttributeName()] = $this->createSlug($title);
    }

    /**
     * Create a slug from a give string. Checks for duplicates until a unique
     * slug is created.
     *
     * @param string|null $title
     * @return string
     */
    public function createSlug(?string $title): string
    {
        $slug = $this->slugify($title);

        if($this->preventDuplicateSlugs()) {
            $totalDuplicates = 0;

            while(static::whereSlug($slug)->count()) {
                $slug = $this->slugify(preg_replace('/-\d+$/', '', $slug) . ' ' . ($totalDuplicates += 1));
            }
        }

        return $slug;
    }

    /**
     * Convert a string to a slug.
     *
     * @param string|null $value
     * @param string|null $delimiter
     * @return string
     */
    public function slugify(?string $value, string $delimiter = null): string
    {
        return str($value)->slug($delimiter ?: $this->getSlugDelimiter());
    }
    
    /**
     * Find model by slug name.
     *
     * @return static
     */
    public static function findBySlug(string $string): static
    {
        return static::slug($string)->firstOrFail();
    }
    
    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootSluggable(): void
    {
        static::saving(function(Model $model) {
            $model->ensureSlugExists($model->title());
        });
    }
}
