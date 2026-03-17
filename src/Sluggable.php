<?php

namespace Actengage\Sluggable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * The sluggable class provides convenience methods to any Eloquent model that
 * needs to have a URI slug associated with it.
 *
 * @mixin Model
 */
trait Sluggable
{
    /**
     * Ensure this instance has a slug.
     */
    public function ensureSlugExists(?string $value): void
    {
        if (! $this->getSlug()) {
            $this->setSlug($value);
        }
    }

    /**
     * Should prevent duplicate slugs or not.
     */
    public function preventDuplicateSlugs(): bool
    {
        return property_exists($this, 'preventDuplicateSlugs')
            ? (bool) $this->preventDuplicateSlugs
            : true;
    }

    /**
     * Get the slug delimiting string.
     */
    public function getSlugDelimiter(): string
    {
        return '-';
    }

    /**
     * Get the slug attribute name.
     */
    public function getSlugAttributeName(): string
    {
        return 'slug';
    }

    /**
     * Get the name of the attribute used to qualify a slug.
     */
    public function getSlugQualifierAttributeName(): string
    {
        return 'title';
    }

    /**
     * Get the slug attribute.
     */
    public function getSlug(): ?string
    {
        $value = $this->getAttribute($this->getSlugAttributeName());

        return is_string($value) ? $value : null;
    }

    /**
     * Set the slug attribute.
     */
    public function setSlug(?string $value): void
    {
        $this->{$this->getSlugAttributeName()} = $value;
    }

    /**
     * Get the slug qualifier.
     */
    public function getSlugQualifier(): ?string
    {
        $value = $this->getAttribute($this->getSlugQualifierAttributeName());

        return is_string($value) ? $value : null;
    }

    /**
     * Set the slug qualifier.
     */
    public function setSlugQualifier(?string $value): void
    {
        $this->{$this->getSlugQualifierAttributeName()} = $value;
    }

    /**
     * Scope a query to find a slug.
     *
     * @param  Builder<static>  $query
     */
    public function scopeSlug(Builder $query, ?string $slug): void
    {
        $query->where($this->getSlugAttributeName(), $slug);
    }

    /**
     * Set the slug attribute.
     */
    public function setSlugAttribute(?string $title): void
    {
        $this->attributes[$this->getSlugAttributeName()] = $this->createSlug($title);
    }

    /**
     * Create a slug from a give string. Checks for duplicates until a unique
     * slug is created.
     */
    public function createSlug(?string $title): string
    {
        $slug = $this->slugify($title);

        if ($this->preventDuplicateSlugs()) {
            $totalDuplicates = 0;

            while ($this->isSlugUnique($slug)) {
                $slug = $this->slugify(preg_replace('/-\d+$/', '', (string) $slug).' '.($totalDuplicates += 1));
            }
        }

        return $slug;
    }

    /**
     * Check if a given slug is unique.
     */
    public function isSlugUnique(string $slug): bool
    {
        return static::slug($slug)->exists();
    }

    /**
     * Convert a string to a slug.
     */
    public function slugify(?string $value, ?string $delimiter = null): string
    {
        return (string) str($value)->slug($delimiter ?: $this->getSlugDelimiter());
    }

    /**
     * Find model by slug name.
     */
    public static function findBySlug(string $string): static
    {
        /** @var static */
        return static::slug($string)->firstOrFail();
    }

    /**
     * Boot the trait.
     */
    public static function bootSluggable(): void
    {
        static::saving(function (self $model): void {
            $model->ensureSlugExists($model->getSlugQualifier());
        });
    }
}
