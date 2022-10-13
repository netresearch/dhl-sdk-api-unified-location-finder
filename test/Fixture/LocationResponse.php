<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Test\Fixture;

class LocationResponse
{
    /**
     * @return string[][]
     */
    public static function getParcelPickupLocationsResponse(): array
    {
        return [
            'json_response' => [
                \file_get_contents(__DIR__ . '/files/success/pickup/parcelResponse.json'),
            ],
        ];
    }

    /**
     * @return string[][]
     */
    public static function getExpressPickupLocationsResponse(): array
    {
        return [
            'json_response' => [
                \file_get_contents(__DIR__ . '/files/success/pickup/expressResponse.json'),
            ],
        ];
    }

    /**
     * @return string[][]
     */
    public static function getParcelDropOffLocationsResponse(): array
    {
        return [
            'json_response' => [
                \file_get_contents(__DIR__ . '/files/success/dropoff/parcelPostOfficeResponse.json'),
                \file_get_contents(__DIR__ . '/files/success/dropoff/parcelLockersResponse.json'),
            ],
        ];
    }

    /**
     * @return string[][]
     */
    public static function getExpressDropOffLocationsResponse(): array
    {
        return [
            'json_response' => [
                \file_get_contents(__DIR__ . '/files/success/pickup/expressResponse.json'),
            ],
        ];
    }

    public static function getParcelPickupLocationsByGeoResponse(): array
    {
        return [
            'json_response' => [
                \file_get_contents(__DIR__ . '/files/success/pickupByGeo/parcelResponse.json'),
            ],
        ];
    }

    public static function getDropOffLocationsByGeoResponse(): array
    {
        return [
            'json_response' => [
                \file_get_contents(__DIR__ . '/files/success/dropoffByGeo/parcelLockersResponse.json'),
                \file_get_contents(__DIR__ . '/files/success/dropoffByGeo/parcelPostOfficeResponse.json'),
            ],
        ];
    }

    /**
     * Read response files and return them as json string
     *
     * @return string[][]
     */
    public static function getErrorResponse(): array
    {
        return [
            '401' => [\file_get_contents(__DIR__ . '/files/error/unauthorized.json')],
            '404' => [\file_get_contents(__DIR__ . '/files/error/not_found.json')],
            '429' => [\file_get_contents(__DIR__ . '/files/error/too_many_requests.json')],
        ];
    }
}
