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
    public function getLong(): float;

    public function getLat(): float;
}
