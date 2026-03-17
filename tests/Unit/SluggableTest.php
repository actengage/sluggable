<?php

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

it('allows duplicate slugs when prevention is disabled', function (): void {
    Page::create(['title' => 'test']);

    $noDupes = new class extends Page
    {
        /** @var bool */
        protected $preventDuplicateSlugs = false;
    };

    $page = $noDupes::create(['title' => 'test']);

    expect($page->slug)->toBe('test');
});
