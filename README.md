# DPDHL Unified Location Finder API SDK

The DPDHL Unified Location Finder API SDK package offers an interface to the following web services:

- Location Finder - Unified

## Requirements

### System Requirements

- PHP 7.0+ with JSON extension

### Package Requirements

- `php-http/discovery`: Discovery service for HTTP client and message factory implementations
- `php-http/httplug`: Pluggable HTTP client abstraction
- `php-http/logger-plugin`: HTTP client logger plugin for HTTPlug
- `php-http/message`: Message factory implementations & message formatter for logging
- `php-http/message-factory`: HTTP message factory interfaces
- `psr/http-message`: PSR-7 HTTP message interfaces
- `psr/log`: PSR-3 logger interfaces

### Virtual Package Requirements

- `php-http/client-implementation`: Any package that provides a HTTPlug HTTP client
- `php-http/message-factory-implementationn`: Any package that provides HTTP message factories
- `psr/http-message-implementation`: Any package that provides PSR-7 HTTP messages

### Development Package Requirements

- `guzzlehttp/psr7`: PSR-7 HTTP message implementation
- `phpunit/phpunit`: Testing framework
- `php-http/mock-client`: HTTPlug mock client implementation
- `phpstan/phpstan`: Static analysis tool
- `squizlabs/php_codesniffer`: Static analysis tool

## Installation

```bash
$ composer require dhl/sdk-api-unified-location-finder
```

## Uninstallation

```bash
$ composer remove dhl/sdk-api-unified-location-finder
```

## Testing

```bash
$ ./vendor/bin/phpunit -c test/phpunit.xml
```

## Features

The DPDHL Unified Location Finder API SDK supports the following features:

* Find DHL Service Points for sending and receiving packages.

## Public API

The library's components suitable for consumption comprise of

* service:
  * service factory
  * location finder service
* data transfer objects:
  * service point location

## Usage

```php
$consumerKey = 'Your application consumer key';
$logger = new \Psr\Log\NullLogger();

$serviceFactory = new \Dhl\Sdk\UnifiedLocationFinder\Service\ServiceFactory();
$service = $serviceFactory->createLocationFinderService($consumerKey, $logger);

try {
    /** @var \Dhl\Sdk\UnifiedLocationFinder\Api\Data\LocationInterface $locations  */
    $locations = $service->getPickUpLocations(
        $countryCode = 'DE',
        $postalCode = '04129',
        $city = 'Leipzig',
        $street = 'Nonnenstraße 11d',
        $service = 'parcel-eu',
        $radius = 2000,
        $limit = 25
    );
} catch (\Dhl\Sdk\UnifiedLocationFinder\Exception\ServiceException $e) {
    // handle errors
}
```

## Error handling

The SDK will only ever throw exceptions of type `\Dhl\Sdk\UnifiedLocationFinder\Exception\ServiceException`.
Subclasses of `ServiceException` may be used to describe the kind of error that occurred. 

A `\Dhl\Sdk\UnifiedLocationFinder\Exception\DetailedServiceException` indicates that the exception holds a
human-readable error message suitable for display to the end-user.
