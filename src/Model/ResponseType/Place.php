<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType;

class Place
{
    private Address $address;

    private Geo $geo;

    private ?ContainedInPlace $containedInPlace = null;

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getGeo(): Geo
    {
        return $this->geo;
    }

    public function getContainedInPlace(): ?ContainedInPlace
    {
        return $this->containedInPlace;
    }
}
