# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.2] - 2026-10-29

### Changed

- GitHub Actions(deps): bump php-fast-forward/dev-tools/.github/workflows/wiki-direct.yml from 2d98b607bf832a82035ddcae3d689aef72842111 to 84e154046e07cafa6ac09a9e12434b1ff5dcb5e0

  Update dependencies: `php-fast-forward/dev-tools/.github/workflows/wiki-direct.yml`.

  ([#13](https://github.com/php-fast-forward/defer/pull/13), @dependabot[bot])

## [1.1.1] - 2026-10-25

### Changed

- Synchronize the bundled local skill automatically and trust Fast Forward Composer
  skill donors through `fast-forward/*`.
  Resolve Changelog contributor skills from stable `0.4.x` releases without a
  redundant skill allowlist.
  Load the project autoloader for PHPDoc checks when DevTools is installed as a
  dependency.

  ([#11](https://github.com/php-fast-forward/defer/pull/11))

- GitHub Actions(deps): bump php-fast-forward/dev-tools/.github/workflows/wiki-direct.yml from c637e7f9d8ae6059b8655996e56f6d17f874792c to 2d98b607bf832a82035ddcae3d689aef72842111

  Update dependencies: `php-fast-forward/dev-tools/.github/workflows/wiki-direct.yml`.

  ([#10](https://github.com/php-fast-forward/defer/pull/10), @dependabot[bot])

## [1.1.0] - 2026-10-21

### Added

- Bundle the fast-forward-defer consumer skill with executable examples, optional installation guidance and Composer discovery metadata. ([#8](https://github.com/php-fast-forward/defer/pull/8))

## [1.0.1] - 2026-10-20

### Changed

- Automate changelog checks, PR feedback and reviewed releases with Fast Forward Changelog, including the project agent skill, version PR titles showing the package and version transition, and historical release notes backfilled from GitHub. ([#6](https://github.com/php-fast-forward/defer/pull/6))
- Restore the Changelog contributor skill through the development-only Composer skill manager and its reviewed GitHub source instead of versioning generated skill copies. ([#6](https://github.com/php-fast-forward/defer/pull/6))

## [1.0.0] - 2026-03-27

**Full Changelog**: https://github.com/php-fast-forward/defer/commits/v1.0.0

[1.1.2]: https://github.com/php-fast-forward/defer/compare/v1.1.1...v1.1.2
[1.1.1]: https://github.com/php-fast-forward/defer/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/php-fast-forward/defer/compare/v1.0.1...v1.1.0
[1.0.1]: https://github.com/php-fast-forward/defer/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/php-fast-forward/defer/releases/tag/v1.0.0
