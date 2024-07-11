<?php

namespace Tests\Unit;

use Tests\Page;
use Tests\TestCase;

class SluggableTest extends TestCase
{
    public function test_creating_sluggable()
    {
        $page = Page::create(['title' => $title = 'This is a test']);
        
        $this->assertTrue(str($title)->kebab()->toString() === $page->slug);
        $this->assertInstanceOf(Page::class, Page::findBySlug($page->slug));
    }
    
    public function test_prevent_duplicate_slug()
    {
        $class = new class extends Page {
            protected $preventDuplicateSlugs = true;
        };

        $page = $class::create(['title' => 'test']);    
        
        $this->assertEquals('test', $page->slug);

        $page = $class::create(['title' => 'test']);
        
        $this->assertEquals('test-1', $page->slug);

        $page = $class::create(['title' => 'test']);
        
        $this->assertEquals('test-2', $page->slug);

        $class = new class extends Page {
            protected $preventDuplicateSlugs = false;
        };

        $page = $class::create(['title' => 'test']);   
        
        $this->assertEquals('test', $page->slug);
    }
    
}
