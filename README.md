# Sluggable

A simple package for managing "slugs" to Eloquent models. Sluggable is a trait
for Eloquent models to ensure a slug exists for the model, and saved it in a
column

### Installation

    composer require actengage/sluggable

### Implementation

To implement Sluggable, you just need to assign the `Sluggable` trait to the
model.
```
    namespace App\Page;

    use Actenage\Sluggable\Sluggable;
    use Illuminate\Database\Eloquent\Model;

    class Page extends Model {

        use Sluggable;

        protected $fillable = [
            'title', 'slug'
        ];

    }

```

### Basic Example
```
$page = Page::create([
    'title' => 'This is some title'
]);

dd($page->slug); // 'this-is-some-page-title'
```