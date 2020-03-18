<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Api;

use Dhl\Sdk\UnifiedLocationFinder\Api\Data\LocationInterface;
use Dhl\Sdk\UnifiedLocationFinder\Exception\AuthenticationException;
use Dhl\Sdk\UnifiedLocationFinder\Exception\DetailedServiceException;
use Dhl\Sdk\UnifiedLocationFinder\Exception\ServiceException;

/**
 * @api
 */
interface LocationFinderServiceInterface
{
    const SERVICE_PARCEL = 'service_parcel';

    const SERVICE_EXPRESS = 'service_express';

    /**
     * Find pickup locations by given address for a given carrier (DHL Paket, DHL Express).
     *
     * @param string $countryCode
     * @param string $postalCode
     * @param string $city
     * @param string $street
     * @param string $service
     * @param int $radius
     * @param int $limit
     *
     * @return LocationInterface[]
     * @throws AuthenticationException
     * @throws DetailedServiceException
     * @throws ServiceException
     */
    public function getPickUpLocations(
        string $countryCode,
        string $postalCode = '',
        string $city = '',
        string $street = '',
        string $service = self::SERVICE_PARCEL,
        int $radius = 500,
        int $limit = 20
    );

    /**
     * Find pickup locations by given coordinates for a given carrier (DHL Paket, DHL Express).
     *
     * @param float $latitude
     * @param float $longitude
     * @param string $service
     * @param int $radius
     * @param int $limit
     *
     * @return LocationInterface[]
     * @throws AuthenticationException
     * @throws DetailedServiceException
     * @throws ServiceException
     */
    public function getPickUpLocationsByCoordinate(
        float $latitude,
        float $longitude,
        string $service = self::SERVICE_PARCEL,
        int $radius = 500,
        int $limit = 20
    );

    /**
     * Find drop-off locations by given address for a given carrier (DHL Paket, DHL Express).
     *
     * @param string $countryCode
     * @param string $postalCode
     * @param string $city
     * @param string $street
     * @param string $service
     * @param int $radius
     * @param int $limit
     *
     * @return LocationInterface[]
     * @throws AuthenticationException
     * @throws DetailedServiceException
     * @throws ServiceException
     */
    public function getDropOffLocations(
        string $countryCode,
        string $postalCode = '',
        string $city = '',
        string $street = '',
        string $service = self::SERVICE_PARCEL,
        int $radius = 500,
        int $limit = 20
    );

    /**
     * Find drop-off locations by given coordinates for a given carrier (DHL Paket, DHL Express).
     *
     * @param float $latitude
     * @param float $longitude
     * @param string $service
     * @param int $radius
     * @param int $limit
     * @return array
     * @throws AuthenticationException
     * @throws DetailedServiceException
     * @throws ServiceException
     */
    public function getDropOffLocationsByCoordinate(
        float $latitude,
        float $longitude,
        string $service = self::SERVICE_PARCEL,
        int $radius = 500,
        int $limit = 20
    );
}
