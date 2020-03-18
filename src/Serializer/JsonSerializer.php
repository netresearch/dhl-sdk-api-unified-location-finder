<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Serializer;

use Dhl\Sdk\UnifiedLocationFinder\Model\FindLocationsResponseType;

class JsonSerializer
{
    /**
     * @param string $jsonResponse
     * @return FindLocationsResponseType
     * @throws \JsonMapper_Exception
     */
    public function decode(string $jsonResponse): FindLocationsResponseType
    {
        $jsonMapper = new \JsonMapper();
        $jsonMapper->bIgnoreVisibility = true;
        $response = \json_decode($jsonResponse, false);
        /** @var FindLocationsResponseType $mappedResponse */
        $mappedResponse = $jsonMapper->map($response, new FindLocationsResponseType());

        return $mappedResponse;
    }
}
