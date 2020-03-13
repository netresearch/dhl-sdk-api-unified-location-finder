<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType;

class Location
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\LocationMeta
     */
    private $location;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $distance;

    /**
     * @var \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\Place
     */
    private $place;

    /**
     * @var string[]
     */
    private $serviceTypes;

    /**
     * @var string
     */
    private $availableCapacity;

    /**
     * @var \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\OpeningHoursSpecification[]
     */
    private $openingHours;

    /**
     * @var \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\ClosurePeriod[]
     */
    private $closurePeriods;

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\LocationMeta
     */
    public function getLocation(): LocationMeta
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getDistance(): int
    {
        return $this->distance;
    }

    /**
     * @return \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\Place
     */
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

    /**
     * @return string
     */
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
