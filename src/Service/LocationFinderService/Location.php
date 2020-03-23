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
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $distanceInMeter;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $number;

    /**
     * @var GeoInterface
     */
    private $geo;

    /**
     * @var AddressInterface
     */
    private $address;

    /**
     * @var OpeningHoursInterface[]
     */
    private $openingHours;

    /**
     * @var OpeningHoursInterface[]
     */
    private $specialOpeningHours;

    /**
     * @var string[]
     */
    private $services;

    /**
     * Location constructor.
     *
     * @param string $id
     * @param string $type
     * @param int $distanceInMeter
     * @param string $name
     * @param string $number
     * @param GeoInterface $geo
     * @param AddressInterface $address
     * @param OpeningHoursInterface[] $openingHours
     * @param OpeningHoursInterface[] $specialOpeningHours
     * @param string[] $services
     */
    public function __construct(
        string $id,
        string $type,
        int $distanceInMeter,
        string $name,
        string $number,
        GeoInterface $geo,
        AddressInterface $address,
        array $openingHours,
        array $specialOpeningHours,
        array $services
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->distanceInMeter = $distanceInMeter;
        $this->name = $name;
        $this->number = $number;
        $this->geo = $geo;
        $this->address = $address;
        $this->openingHours = $openingHours;
        $this->specialOpeningHours = $specialOpeningHours;
        $this->services = $services;
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
