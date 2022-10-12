<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType;

class Place
{
    /**
     * @var \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\Address
     */
    private $address;

    /**
     * @var \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\Geo
     */
    private $geo;

    /**
     * @var \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\ContainedInPlace|null
     */
    private $containedInPlace;

    /**
     * @return \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @return \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\Geo
     */
    public function getGeo(): Geo
    {
        return $this->geo;
    }

    /**
     * @return \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\ContainedInPlace|null
     */
    public function getContainedInPlace(): ?ContainedInPlace
    {
        return $this->containedInPlace;
    }
}
