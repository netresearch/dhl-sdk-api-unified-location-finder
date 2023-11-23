<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Model;

class FindLocationsResponseType
{
    /**
     * @var \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\Location[]
     */
    private array $locations;

    /**
     * @return \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\Location[]
     */
    public function getLocations(): array
    {
        return $this->locations;
    }
}
