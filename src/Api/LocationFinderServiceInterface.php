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
    public const SERVICE_PARCEL = 'parcel-eu';

    public const SERVICE_EXPRESS = 'express';

    /**
     * Find pickup locations by given address for a given carrier (DHL Paket, DHL Express).
     *
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
        ?int $radius = null,
        ?int $limit = null
    ): array;

    /**
     * Find pickup locations by given coordinates for a given carrier (DHL Paket, DHL Express).
     *
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
        ?int $radius = null,
        ?int $limit = null
    ): array;

    /**
     * Find drop-off locations by given address for a given carrier (DHL Paket, DHL Express).
     *
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
        ?int $radius = null,
        ?int $limit = null
    ): array;

    /**
     * Find drop-off locations by given coordinates for a given carrier (DHL Paket, DHL Express).
     *
     *
     * @return LocationInterface[]
     * @throws AuthenticationException
     * @throws DetailedServiceException
     * @throws ServiceException
     */
    public function getDropOffLocationsByCoordinate(
        float $latitude,
        float $longitude,
        string $service = self::SERVICE_PARCEL,
        ?int $radius = null,
        ?int $limit = null
    ): array;
}
