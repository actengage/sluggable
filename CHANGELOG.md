# Changelog

## 7.0.0

### Major Changes

- [#8](https://github.com/actengage/sluggable/pull/8) [`4712c2e`](https://github.com/actengage/sluggable/commit/4712c2ebb7c0cb51a53817da3023ec574cdf9a18) Thanks [@actengage](https://github.com/actengage)! - ### Laravel 13 Support

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

## 7.0.0

### Major Changes

- [#5](https://github.com/actengage/sluggable/pull/5) [`7a588a5`](https://github.com/actengage/sluggable/commit/7a588a534f0e2907515b435f2858dc05bdb82ab8) Thanks [@actengage](https://github.com/actengage)! - ### Laravel 13 Support

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

  ### Dev Tooling

  - Added Pint, PHPStan (level 10), Rector, and Pest
  - Internal code style cleanup and refactoring
