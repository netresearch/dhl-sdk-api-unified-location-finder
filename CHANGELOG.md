# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

### Added

- Introduce `\Dhl\Sdk\UnifiedLocationFinder\Api\Data\LocationInterface::getPlace()` method
  to retrieve additional information about the surroundings where the facility is located.

### Changed

- Use service type `parcel:pick-up-all` to reduce web service calls.

## 2.1.0

### Added

- Support for PHP 8

### Removed

- Support for PHP 7.1

## 2.0.0

### Changed

- HTTPlug package is upgraded to version 2.
- PHP-HTTP packages are replaced by their PSR successors. SDK now requires a `psr/http-client-implementation`.

### Removed

- PHP 7.0 is no longer supported.

## 1.0.0

- Initial release
