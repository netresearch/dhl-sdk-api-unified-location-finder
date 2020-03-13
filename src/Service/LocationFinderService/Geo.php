<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Service\LocationFinderService;

use Dhl\Sdk\UnifiedLocationFinder\Api\Data\GeoInterface;

class Geo implements GeoInterface
{
    /**
     * @var float
     */
    private $long;

    /**
     * @var float
     */
    private $lat;

    public function __construct(float $long, float $lat)
    {
        $this->long = $long;
        $this->lat = $lat;
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
