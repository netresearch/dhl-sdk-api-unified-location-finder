<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType;

class Location
{
    private string $url = '';

    private LocationMeta $location;

    private string $name = '';

    private int $distance;

    private Place $place;

    /**
     * @var string[]
     */
    private array $serviceTypes;

    private string $availableCapacity = '';

    /**
     * @var \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\OpeningHoursSpecification[]
     */
    private array $openingHours;

    /**
     * @var \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\ClosurePeriod[]
     */
    private array $closurePeriods;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getLocation(): LocationMeta
    {
        return $this->location;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDistance(): int
    {
        return $this->distance;
    }

    public function getPlace(): Place
    {
        return $this->place;
    }

    /**
     * @return string[]
     */
    public function getServiceTypes(): array
    {
        return $this->serviceTypes;
    }

    public function getAvailableCapacity(): string
    {
        return $this->availableCapacity;
    }

    /**
     * @return \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\OpeningHoursSpecification[]
     */
    public function getOpeningHours(): array
    {
        return $this->openingHours;
    }

    /**
     * @return \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\ClosurePeriod[]
     */
    public function getClosurePeriods(): array
    {
        return $this->closurePeriods;
    }
}
