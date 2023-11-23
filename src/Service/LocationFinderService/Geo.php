<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Service\LocationFinderService;

use Dhl\Sdk\UnifiedLocationFinder\Api\Data\GeoInterface;

class Geo implements GeoInterface
{
    public function __construct(private readonly float $long, private readonly float $lat)
    {
    }

    public function getLong(): float
    {
        return $this->long;
    }

    public function getLat(): float
    {
        return $this->lat;
    }
}
