<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Service\LocationFinderService;

use Dhl\Sdk\UnifiedLocationFinder\Api\Data\AddressInterface;
use Dhl\Sdk\UnifiedLocationFinder\Api\Data\GeoInterface;
use Dhl\Sdk\UnifiedLocationFinder\Api\Data\LocationInterface;
use Dhl\Sdk\UnifiedLocationFinder\Api\Data\OpeningHoursInterface;

class Location implements LocationInterface
{
    /**
     * Location constructor.
     *
     * @param OpeningHoursInterface[] $openingHours
     * @param OpeningHoursInterface[] $specialOpeningHours
     * @param string[] $services
     */
    public function __construct(
        private readonly string $id,
        private readonly string $type,
        private readonly int $distanceInMeter,
        private readonly string $name,
        private readonly string $number,
        private readonly GeoInterface $geo,
        private readonly AddressInterface $address,
        private readonly string $place,
        private readonly array $openingHours,
        private readonly array $specialOpeningHours,
        private readonly array $services
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDistanceInMeter(): int
    {
        return $this->distanceInMeter;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getGeo(): GeoInterface
    {
        return $this->geo;
    }

    public function getAddress(): AddressInterface
    {
        return $this->address;
    }

    public function getPlace(): string
    {
        return $this->place;
    }

    public function getOpeningHours(): array
    {
        return $this->openingHours;
    }

    public function getSpecialOpeningHours(): array
    {
        return $this->specialOpeningHours;
    }

    public function getServices(): array
    {
        return $this->services;
    }
}
