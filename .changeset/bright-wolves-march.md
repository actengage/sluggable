---
"actengage-sluggable": major
---

### Laravel 13 Support

Added `^13.0` to the `laravel/framework` constraint alongside `^11.0` and `^12.0`.

### PHP Attributes for Declarative Configuration

New PHP attributes to configure sluggable behavior without overriding methods:

- `#[Slug('name')]` — set the qualifier attribute
- `#[SlugAttribute('url_slug')]` — set the slug column name
- `#[SlugDelimiter('_')]` — set the slug delimiter
- `#[PreventDuplicateSlugs(false)]` — control duplicate slug prevention

### CI/CD

- Added GitHub Actions CI workflow with Pint, PHPStan, Rector, and Pest running in parallel
- Added GitHub Actions release workflow with changesets for automated versioning
- Release workflow depends on CI passing before creating releases

### Dev Tooling

- Added Pint, PHPStan (level 10), Rector, and Pest
- Internal code style cleanup and refactoring
