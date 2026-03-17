<?php

use Actengage\Sluggable\PreventDuplicateSlugs;
use Actengage\Sluggable\Slug;
use Actengage\Sluggable\SlugAttribute;
use Actengage\Sluggable\SlugDelimiter;
use Tests\Page;

it('creates a slug from the title', function (): void {
    $page = Page::create(['title' => $title = 'This is a test']);

    expect($page->slug)->toBe(str($title)->kebab()->toString());
    expect(Page::findBySlug((string) $page->slug))->toBeInstanceOf(Page::class);
});

it('sets the slug qualifier', function (): void {
    $page = new Page;
    $page->setSlugQualifier('New Title');

    expect($page->title)->toBe('New Title');
});

it('prevents duplicate slugs', function (): void {
    $page = Page::create(['title' => 'test']);
    expect($page->slug)->toBe('test');

    $page = Page::create(['title' => 'test']);
    expect($page->slug)->toBe('test-1');

    $page = Page::create(['title' => 'test']);
    expect($page->slug)->toBe('test-2');
});

it('allows duplicate slugs when prevention is disabled via property', function (): void {
    Page::create(['title' => 'test']);

    $noDupes = new class extends Page
    {
        /** @var bool */
        protected $preventDuplicateSlugs = false;
    };

    $page = $noDupes::create(['title' => 'test']);

    expect($page->slug)->toBe('test');
});

it('uses the Slug attribute for qualifier', function (): void {
    $model = new #[Slug('title')] class extends Page {};

    expect($model->getSlugQualifierAttributeName())->toBe('title');
});

it('uses the Slug attribute for custom qualifier', function (): void {
    $model = new #[Slug('name')] class extends Page
    {
        protected $fillable = ['name', 'slug'];
    };

    expect($model->getSlugQualifierAttributeName())->toBe('name');
});

it('uses the SlugAttribute for custom attribute name', function (): void {
    $model = new #[SlugAttribute('url_slug')] class extends Page {};

    expect($model->getSlugAttributeName())->toBe('url_slug');
});

it('uses the SlugDelimiter attribute for custom delimiter', function (): void {
    $model = new #[SlugDelimiter('_')] class extends Page {};

    expect($model->getSlugDelimiter())->toBe('_');
    expect($model->slugify('hello world'))->toBe('hello_world');
});

it('uses the PreventDuplicateSlugs attribute to disable unique slugs', function (): void {
    Page::create(['title' => 'test']);

    $model = new #[PreventDuplicateSlugs(false)] class extends Page {};

    $page = $model::create(['title' => 'test']);

    expect($page->slug)->toBe('test');
});

it('uses the PreventDuplicateSlugs attribute to enable unique slugs', function (): void {
    Page::create(['title' => 'test']);

    $model = new #[PreventDuplicateSlugs] class extends Page {};

    $page = $model::create(['title' => 'test']);

    expect($page->slug)->toBe('test-1');
});

it('uses defaults when no attributes are present', function (): void {
    $page = new Page;

    expect($page->getSlugQualifierAttributeName())->toBe('title');
    expect($page->getSlugAttributeName())->toBe('slug');
    expect($page->getSlugDelimiter())->toBe('-');
    expect($page->preventDuplicateSlugs())->toBeTrue();
});

it('returns null for getSlug when attribute is not a string', function (): void {
    $page = new Page;

    expect($page->getSlug())->toBeNull();
});

it('returns null for getSlugQualifier when attribute is not a string', function (): void {
    $page = new Page;

    expect($page->getSlugQualifier())->toBeNull();
});

it('scopes query by slug', function (): void {
    $page = Page::create(['title' => 'test']);
    $found = Page::slug('test')->firstOrFail();

    expect($found->id)->toBe($page->id);
});
