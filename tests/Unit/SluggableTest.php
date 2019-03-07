<?php

namespace Tests\Unit;

use Tests\Page;
use Tests\TestCase;

class SluggableTest extends TestCase
{
    public function testCreateModel()
    {
        $page = Page::create([
            'title' => $title = 'This is a test'
        ]);
        
        $this->assertTrue(kebab_case($title) === $page->slug);
    }
    
    public function testFindBySlug()
    {
        $page = Page::create([
            'title' => $title = 'This is a test'
        ]);
        
        $this->assertInstanceOf(Page::class, Page::find($page->title));
    }
    

}
