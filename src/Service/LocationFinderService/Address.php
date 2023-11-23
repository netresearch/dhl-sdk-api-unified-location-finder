<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Service\LocationFinderService;

use Dhl\Sdk\UnifiedLocationFinder\Api\Data\AddressInterface;

class Address implements AddressInterface
{
    public function __construct(
        private readonly string $countryCode,
        private readonly string $postalCode,
        private readonly string $city,
        private readonly string $street,
    ) {
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getStreet(): string
    {
        return $this->street;
    }
}
