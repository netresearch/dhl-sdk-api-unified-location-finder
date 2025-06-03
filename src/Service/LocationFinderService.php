<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Service;

use Dhl\Sdk\UnifiedLocationFinder\Api\Data\LocationInterface;
use Dhl\Sdk\UnifiedLocationFinder\Api\LocationFinderServiceInterface;
use Dhl\Sdk\UnifiedLocationFinder\Exception\AuthenticationErrorException;
use Dhl\Sdk\UnifiedLocationFinder\Exception\AuthenticationException;
use Dhl\Sdk\UnifiedLocationFinder\Exception\DetailedErrorException;
use Dhl\Sdk\UnifiedLocationFinder\Exception\DetailedServiceException;
use Dhl\Sdk\UnifiedLocationFinder\Exception\ServiceException;
use Dhl\Sdk\UnifiedLocationFinder\Exception\ServiceExceptionFactory;
use Dhl\Sdk\UnifiedLocationFinder\Model\LocationResponseMapper;
use Dhl\Sdk\UnifiedLocationFinder\Serializer\JsonSerializer;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class LocationFinderService implements LocationFinderServiceInterface
{
    public function __construct(
        private readonly ClientInterface $client,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly JsonSerializer $serializer,
        private readonly LocationResponseMapper $responseMapper
    ) {
    }

    /**
     * @param string[]|int[]|float[] $queryParams
     *
     * @return LocationInterface[]
     * @throws AuthenticationException
     * @throws DetailedServiceException
     * @throws ServiceException
     */
    private function performRequest(string $resource, array $queryParams): array
    {
        $queryParams = http_build_query($queryParams);
        $uri = "https://api.dhl.com/location-finder/v1/$resource?$queryParams";

        try {
            $request = $this->requestFactory->createRequest('GET', $uri);
            $response = $this->client->sendRequest($request);
            $responseJson = (string)$response->getBody();
            $responseObject = $this->serializer->decode($responseJson);
        } catch (AuthenticationErrorException $exception) {
            throw ServiceExceptionFactory::createAuthenticationException($exception);
        } catch (DetailedErrorException $exception) {
            throw ServiceExceptionFactory::createDetailedServiceException($exception);
        } catch (ClientExceptionInterface $exception) {
            throw ServiceExceptionFactory::createServiceException($exception);
        } catch (\Throwable $exception) {
            throw ServiceExceptionFactory::create($exception);
        }

        return $this->responseMapper->map($responseObject->getLocations());
    }

    public function getPickUpLocations(
        string $countryCode,
        string $postalCode = '',
        string $city = '',
        string $street = '',
        string $service = self::SERVICE_PARCEL,
        ?int $radius = null,
        ?int $limit = null
    ): array {
        /** @var string[]|int[]|float[] $requestParams */
        $requestParams = array_filter([
            'countryCode' => $countryCode,
            'postalCode' => $postalCode,
            'addressLocality' => $city,
            'streetAddress' => $street,
            'radius' => $radius,
            'limit' => $limit,
            'serviceType' => ($service === self::SERVICE_EXPRESS) ? 'express:pick-up' : 'parcel:pick-up-all',
        ]);

        return $this->performRequest('find-by-address', $requestParams);
    }

    public function getPickUpLocationsByCoordinate(
        float $latitude,
        float $longitude,
        string $service = self::SERVICE_PARCEL,
        ?int $radius = null,
        ?int $limit = null
    ): array {
        /** @var string[]|int[]|float[] $requestParams */
        $requestParams = array_filter([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'radius' => $radius,
            'limit' => $limit,
            'serviceType' => ($service === self::SERVICE_EXPRESS) ? 'express:pick-up' : 'parcel:pick-up-all',
        ]);

        return $this->performRequest('find-by-geo', $requestParams);
    }

    public function getDropOffLocations(
        string $countryCode,
        string $postalCode = '',
        string $city = '',
        string $street = '',
        string $service = self::SERVICE_PARCEL,
        ?int $radius = null,
        ?int $limit = null
    ): array {
        /** @var string[]|int[]|float[] $requestParams */
        $requestParams = array_filter([
            'countryCode' => $countryCode,
            'postalCode' => $postalCode,
            'addressLocality' => $city,
            'streetAddress' => $street,
            'radius' => $radius,
            'limit' => $limit
        ]);
        if ($service === self::SERVICE_EXPRESS) {
            $requestParams['serviceType'] = 'express:drop-off';
            return $this->performRequest('find-by-address', $requestParams);
        }
        $requestParams['serviceType'] = 'parcel:drop-off-unregistered';
        $lockers = $this->performRequest('find-by-address', $requestParams);
        $requestParams['serviceType'] = 'parcel:drop-off';

        return array_merge($lockers, $this->performRequest('find-by-address', $requestParams));
    }

    public function getDropOffLocationsByCoordinate(
        float $latitude,
        float $longitude,
        string $service = self::SERVICE_PARCEL,
        ?int $radius = null,
        ?int $limit = null
    ): array {
        /** @var string[]|int[]|float[] $requestParams */
        $requestParams = array_filter([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'radius' => $radius,
            'limit' => $limit
        ]);
        if ($service === self::SERVICE_EXPRESS) {
            $requestParams['serviceType'] = 'express:drop-off';
            return $this->performRequest('find-by-geo', $requestParams);
        }
        $requestParams['serviceType'] = 'parcel:drop-off-unregistered';
        $lockers = $this->performRequest('find-by-geo', $requestParams);
        $requestParams['serviceType'] = 'parcel:drop-off';

        return array_merge($lockers, $this->performRequest('find-by-geo', $requestParams));
    }
}
