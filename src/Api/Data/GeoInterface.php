<?php

namespace Dhl\Sdk\UnifiedLocationFinder\Api\Data;

/**
 * @api
 */
interface GeoInterface
{
    /**
     * @return float
     */
    public function getLong(): float;

    /**
     * @return float
     */
    public function getLat(): float;
}
