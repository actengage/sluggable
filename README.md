# Sluggable

A simple package for managing "slugs" on Eloquent models. Sluggable is a trait
for Eloquent models to ensure a slug exists for the model and is saved in a column.

### Installation

    composer require actengage/sluggable

### Implementation

Add the `Sluggable` trait to your model.

```php
namespace App\Models;

use Actengage\Sluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use Sluggable;

    protected $fillable = [
        'title', 'slug',
    ];
}
```

### Basic Example

```php
$page = Page::create([
    'title' => 'This is some title',
]);

$page->slug; // 'this-is-some-title'
```

### PHP Attributes

You can use PHP attributes to declaratively configure sluggable behavior instead of overriding methods.

#### `#[Slug]` — Set the qualifier

The qualifier is the model attribute used to generate the slug. Defaults to `title`.

```php
use Actengage\Sluggable\Slug;

#[Slug('name')]
class Product extends Model
{
    use Sluggable;
}
```

#### `#[SlugAttribute]` — Set the slug column

The column where the slug is stored. Defaults to `slug`.

```php
use Actengage\Sluggable\SlugAttribute;

#[SlugAttribute('url_slug')]
class Product extends Model
{
    use Sluggable;
}
```

#### `#[SlugDelimiter]` — Set the delimiter

The character used to separate words in the slug. Defaults to `-`.

```php
use Actengage\Sluggable\SlugDelimiter;

#[SlugDelimiter('_')]
class Product extends Model
{
    use Sluggable;
}
```

#### `#[PreventDuplicateSlugs]` — Control duplicate prevention

By default, duplicate slugs are prevented by appending an incrementing number. Use this attribute to disable that behavior.

```php
use Actengage\Sluggable\PreventDuplicateSlugs;

#[PreventDuplicateSlugs(false)]
class Product extends Model
{
    use Sluggable;
}
```

#### Combining attributes

```php
use Actengage\Sluggable\PreventDuplicateSlugs;
use Actengage\Sluggable\Slug;
use Actengage\Sluggable\SlugAttribute;
use Actengage\Sluggable\SlugDelimiter;

#[Slug('name')]
#[SlugAttribute('url_slug')]
#[SlugDelimiter('_')]
#[PreventDuplicateSlugs(false)]
class Product extends Model
{
    use Sluggable;
}
```

### Finding by Slug

```php
$page = Page::findBySlug('this-is-some-title');
```

### Slug Scope

```php
$page = Page::slug('this-is-some-title')->first();
```

### Duplicate Slug Prevention

By default, Sluggable prevents duplicate slugs by appending an incrementing number.

```php
Page::create(['title' => 'test']); // slug: 'test'
Page::create(['title' => 'test']); // slug: 'test-1'
Page::create(['title' => 'test']); // slug: 'test-2'
```

This can be disabled with the `#[PreventDuplicateSlugs]` attribute or by setting the property on the model:

```php
class Page extends Model
{
    use Sluggable;

    protected $preventDuplicateSlugs = false;
}
```
