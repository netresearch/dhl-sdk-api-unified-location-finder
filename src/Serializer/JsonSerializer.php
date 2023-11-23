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
     * @throws \JsonMapper_Exception
     * @throws \JsonException
     */
    public function decode(string $jsonResponse): FindLocationsResponseType
    {
        $jsonMapper = new \JsonMapper();
        $jsonMapper->bIgnoreVisibility = true;
        $response = \json_decode($jsonResponse, false, 512, JSON_THROW_ON_ERROR);
        /** @var FindLocationsResponseType $mappedResponse */
        $mappedResponse = $jsonMapper->map($response, new FindLocationsResponseType());

        return $mappedResponse;
    }
}
