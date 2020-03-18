<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

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
