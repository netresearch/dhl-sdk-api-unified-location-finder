<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Test\Service;

use Dhl\Sdk\UnifiedLocationFinder\Api\LocationFinderServiceInterface;
use Dhl\Sdk\UnifiedLocationFinder\Exception\AuthenticationException;
use Dhl\Sdk\UnifiedLocationFinder\Exception\DetailedServiceException;
use Dhl\Sdk\UnifiedLocationFinder\Exception\ServiceException;
use Dhl\Sdk\UnifiedLocationFinder\Http\HttpServiceFactory;
use Dhl\Sdk\UnifiedLocationFinder\Test\Expectation\LocationFinderServiceTestExpectation as Expectation;
use Dhl\Sdk\UnifiedLocationFinder\Test\Fixture\LocationResponse;
use Http\Client\Common\Exception\LoopException;
use Http\Client\Exception\HttpException;
use Http\Client\Exception\NetworkException;
use Http\Client\Exception\RequestException;
use Http\Client\Exception\TransferException;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class LocationFinderServiceTest extends TestCase
{
    /**
     * @return TransferException[][]
     */
    public static function exceptionProvider(): array
    {
        $messageFactory = Psr17FactoryDiscovery::findResponseFactory();
        $request = $messageFactory->createRequest('GET', 'www');

        return [
            HttpException::class => [
                'exception' => new HttpException(
                    'ERROR',
                    $request,
                    $messageFactory->createResponse(500)
                ),
            ],
            NetworkException::class => [
                'exception' => new NetworkException(
                    'ERROR',
                    $request
                ),
            ],
            LoopException::class => [
                'exception' => new LoopException(
                    'ERROR',
                    $request
                ),
            ],
            RequestException::class => [
                'exception' => new RequestException(
                    'ERROR',
                    $request
                ),
            ],
            TransferException::class => ['exception' => new TransferException('ERROR')],
        ];
    }

    /**
     * @return string[][]
     */
    public static function errorDataProvider(): array
    {
        return LocationResponse::getErrorResponse();
    }

    /**
     * @return string[][]
     */
    public static function parcelPickupLocationsProvider(): array
    {
        return LocationResponse::getParcelPickupLocationsResponse();
    }

    /**
     * @return string[][]
     */
    public static function parcelDropOffLocationsProvider(): array
    {
        return LocationResponse::getParcelDropOffLocationsResponse();
    }

    /**
     * @return string[][]
     */
    public static function expressPickupLocationsProvider(): array
    {
        return LocationResponse::getExpressPickupLocationsResponse();
    }

    /**
     * @return string[][]
     */
    public static function expressDropOffLocationsProvider(): array
    {
        return LocationResponse::getExpressDropOffLocationsResponse();
    }

    /**
     * @return string[][]
     */
    public static function geoPickupLocationsProvider(): array
    {
        return LocationResponse::getParcelPickupLocationsByGeoResponse();
    }

    /**
     * @return string[][]
     */
    public static function geoDropOffLocationsProvider(): array
    {
        return LocationResponse::getDropOffLocationsByGeoResponse();
    }

    /**
     * Assert that HTTP client exceptions with no JSON response are transformed into service exceptions.
     *
     *
     * @throws ServiceException
     */
    #[DataProvider('exceptionProvider')]
    #[Test]
    public function handleExceptions(\Throwable $exception): void
    {
        $this->expectException(ServiceException::class);

        $logger = new TestLogger();
        $client = new Client();
        $client->setDefaultException($exception);

        $serviceFactory = new HttpServiceFactory($client);
        $service = $serviceFactory->createLocationFinderService('FOO_KEY', $logger);

        try {
            $service->getPickUpLocations('DE', '04275');
        } catch (ServiceException $exception) {
            Expectation::assertExceptionLogged($exception, $logger);

            throw $exception;
        }
    }

    /**
     * Assert that HTTP client exceptions with JSON response are transformed into detailed service exceptions.
     *
     *
     * @throws ServiceException
     */
    #[DataProvider('errorDataProvider')]
    #[Test]
    public function handleErrors(string $jsonResponse): void
    {
        $response = json_decode($jsonResponse, true, 512, JSON_THROW_ON_ERROR);

        if ($response['status'] === 401) {
            $this->expectException(AuthenticationException::class);
        } else {
            $this->expectException(DetailedServiceException::class);
        }

        $this->expectExceptionCode($response['status']);

        $logger = new TestLogger();
        $client = new Client();

        $messageFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $httpResponse = $messageFactory
            ->createResponse($response['status'], $response['title'])
            ->withHeader('Content-Type', 'application/json')
            ->withBody($streamFactory->createStream($jsonResponse));

        $client->setDefaultResponse($httpResponse);

        $serviceFactory = new HttpServiceFactory($client);
        $service = $serviceFactory->createLocationFinderService('FOO_KEY', $logger);

        try {
            $service->getPickUpLocations('DE');
        } catch (ServiceException $exception) {
            Expectation::assertErrorLogged($jsonResponse, $client->getLastRequest(), $logger);

            $this->assertNotFalse(strpos($exception->getMessage(), (string) $response['title']));
            throw $exception;
        }
    }

    /**
     * Scenario: query pickup locations for DHL Paket carrier.
     *
     * - Assert arguments being passed to web service
     * - Assert request and response being logged
     * - Assert web service response being properly transformed to SDK response objects.
     *
     *
     * @throws ServiceException
     */
    #[DataProvider('parcelPickupLocationsProvider')]
    #[Test]
    public static function findParcelPickupLocations(string $jsonResponse): void
    {
        $messageFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $logger = new TestLogger();
        $client = new Client();
        $client->addResponse(
            $messageFactory
                ->createResponse(200, 'OK')
                ->withBody($streamFactory->createStream($jsonResponse))
                ->withHeader('Content-Type', 'application/json')
        );

        $serviceFactory = new HttpServiceFactory($client);
        $service = $serviceFactory->createLocationFinderService('FOO_KEY', $logger);

        $result = $service->getPickUpLocations(
            $countryCode = 'DE',
            $postalCode = '04229',
            $city = 'Leipzig',
            $street = 'Klingerweg',
            $service = LocationFinderServiceInterface::SERVICE_PARCEL,
            $radius = 7500,
            $limit = 25
        );

        Expectation::assertQuery(
            $client->getLastRequest(),
            $service,
            $countryCode,
            $postalCode,
            $city,
            $street,
            null,
            null,
            $radius,
            $limit
        );
        Expectation::assertCommunicationLogged($jsonResponse, $client->getLastRequest(), $logger);
        Expectation::assertLocationsMapped($jsonResponse, $result);
    }

    /**
     * Scenario: query pickup locations for DHL Express carrier.
     *
     * - Assert arguments being passed to web service
     * - Assert request and response being logged
     * - Assert web service response being properly transformed to SDK response objects.
     *
     *
     * @throws ServiceException
     */
    #[DataProvider('expressPickupLocationsProvider')]
    #[Test]
    public static function findExpressPickupLocations(string $jsonResponse): void
    {
        $messageFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $logger = new TestLogger();
        $client = new Client();
        $client->addResponse(
            $messageFactory
                ->createResponse(200, 'OK')
                ->withBody($streamFactory->createStream($jsonResponse))
                ->withHeader('Content-Type', 'application/json')
        );

        $serviceFactory = new HttpServiceFactory($client);
        $service = $serviceFactory->createLocationFinderService('FOO_KEY', $logger);

        $result = $service->getPickUpLocations(
            $countryCode = 'DE',
            $postalCode = '04229',
            $city = 'Leipzig',
            $street = 'Klingerweg',
            $service = LocationFinderServiceInterface::SERVICE_EXPRESS,
            $radius = 7500,
            $limit = 25
        );

        Expectation::assertQuery(
            $client->getLastRequest(),
            $service,
            $countryCode,
            $postalCode,
            $city,
            $street,
            null,
            null,
            $radius,
            $limit
        );
        Expectation::assertCommunicationLogged($jsonResponse, $client->getLastRequest(), $logger);
        Expectation::assertLocationsMapped($jsonResponse, $result);
    }

    /**
     * Scenario: query drop-off locations for DHL Paket carrier.
     *
     * - Assert arguments being passed to web service
     * - Assert request and response being logged
     * - Assert web service response being properly transformed to SDK response objects.
     *
     *
     * @throws ServiceException
     */
    #[DataProvider('parcelDropOffLocationsProvider')]
    #[Test]
    public static function findParcelDropOffLocations(string $postOfficesResponse, string $lockersResponse): void
    {
        $messageFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $logger = new TestLogger();
        $client = new Client();
        $client->addResponse(
            $messageFactory
                ->createResponse(200, 'OK')
                ->withBody($streamFactory->createStream($lockersResponse))
                ->withHeader('Content-Type', 'application/json')
        );
        $client->addResponse(
            $messageFactory
                ->createResponse(200, 'OK')
                ->withBody($streamFactory->createStream($postOfficesResponse))
                ->withHeader('Content-Type', 'application/json')
        );

        $serviceFactory = new HttpServiceFactory($client);
        $service = $serviceFactory->createLocationFinderService('FOO_KEY', $logger);

        $result = $service->getDropOffLocations(
            $countryCode = 'DE',
            $postalCode = '04229',
            $city = 'Leipzig',
            $street = 'Klingerweg',
            $service = LocationFinderServiceInterface::SERVICE_PARCEL,
            $radius = 7500,
            $limit = 25
        );

        Expectation::assertQuery(
            $client->getRequests()[0],
            $service,
            $countryCode,
            $postalCode,
            $city,
            $street,
            null,
            null,
            $radius,
            $limit
        );
        Expectation::assertQuery(
            $client->getRequests()[1],
            $service,
            $countryCode,
            $postalCode,
            $city,
            $street,
            null,
            null,
            $radius,
            $limit
        );
        Expectation::assertCommunicationLogged($postOfficesResponse, $client->getRequests()[0], $logger);
        Expectation::assertCommunicationLogged($lockersResponse, $client->getRequests()[1], $logger);

        $locations = array_merge(
            json_decode($lockersResponse, true, 512, JSON_THROW_ON_ERROR)['locations'],
            json_decode($postOfficesResponse, true, 512, JSON_THROW_ON_ERROR)['locations']
        );

        Expectation::assertLocationsMapped(json_encode(['locations' => $locations], JSON_THROW_ON_ERROR), $result);
    }

    /**
     * Scenario: query drop-off locations for DHL Express carrier.
     *
     * - Assert arguments being passed to web service
     * - Assert request and response being logged
     * - Assert web service response being properly transformed to SDK response objects.
     *
     *
     * @throws ServiceException
     */
    #[DataProvider('expressDropOffLocationsProvider')]
    #[Test]
    public static function findExpressDropOffLocations(string $jsonResponse): void
    {
        $messageFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $logger = new TestLogger();
        $client = new Client();

        $client->addResponse(
            $messageFactory
                ->createResponse(200, 'OK')
                ->withBody($streamFactory->createStream($jsonResponse))
                ->withHeader('Content-Type', 'application/json')
        );

        $serviceFactory = new HttpServiceFactory($client);
        $service = $serviceFactory->createLocationFinderService('FOO_KEY', $logger);

        $result = $service->getDropOffLocations(
            $countryCode = 'DE',
            $postalCode = '04229',
            $city = 'Leipzig',
            $street = 'Klingerweg',
            $service = LocationFinderServiceInterface::SERVICE_EXPRESS,
            $radius = 7500,
            $limit = 25
        );

        Expectation::assertQuery(
            $client->getLastRequest(),
            $service,
            $countryCode,
            $postalCode,
            $city,
            $street,
            null,
            null,
            $radius,
            $limit
        );
        Expectation::assertCommunicationLogged($jsonResponse, $client->getLastRequest(), $logger);
        Expectation::assertLocationsMapped($jsonResponse, $result);
    }

    /**
     * Scenario: query pickup locations for DHL Paket carrier by geo coordinates.
     *
     * - Assert arguments being passed to web service
     *
     *
     * @throws ServiceException
     */
    #[DataProvider('geoPickupLocationsProvider')]
    #[Test]
    public static function findPickUpLocationsByGeo(string $jsonResponse): void
    {
        $messageFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $logger = new TestLogger();
        $client = new Client();
        $client->addResponse(
            $messageFactory
                ->createResponse(200, 'OK')
                ->withBody($streamFactory->createStream($jsonResponse))
                ->withHeader('Content-Type', 'application/json')
        );

        $serviceFactory = new HttpServiceFactory($client);
        $service = $serviceFactory->createLocationFinderService('FOO_KEY', $logger);

        $service->getPickUpLocationsByCoordinate(
            $lat = 50.7169763,
            $long = 7.1329916,
            $service = LocationFinderServiceInterface::SERVICE_PARCEL,
            $radius = 7500,
            $limit = 25
        );

        Expectation::assertQuery(
            $client->getLastRequest(),
            $service,
            null,
            null,
            null,
            null,
            $lat,
            $long,
            $radius,
            $limit
        );
    }

    /**
     * Scenario: query drop-off locations for DHL Paket carrier by geo coordinates.
     *
     * - Assert arguments being passed to web service
     *
     *
     * @throws ServiceException
     */
    #[DataProvider('geoDropOffLocationsProvider')]
    #[Test]
    public static function findDropOffLocationsByGeo(string $lockersResponse, string $postOfficesResponse): void
    {
        $messageFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $logger = new TestLogger();
        $client = new Client();
        $client->addResponse(
            $messageFactory
                ->createResponse(200, 'OK')
                ->withBody($streamFactory->createStream($lockersResponse))
                ->withHeader('Content-Type', 'application/json')
        );
        $client->addResponse(
            $messageFactory
                ->createResponse(200, 'OK')
                ->withBody($streamFactory->createStream($postOfficesResponse))
                ->withHeader('Content-Type', 'application/json')
        );

        $serviceFactory = new HttpServiceFactory($client);
        $service = $serviceFactory->createLocationFinderService('FOO_KEY', $logger);

        $service->getDropOffLocationsByCoordinate(
            $lat = 30.333,
            $long = 10.555,
            $service = LocationFinderServiceInterface::SERVICE_PARCEL,
            $radius = 7500,
            $limit = 25
        );

        Expectation::assertQuery(
            $client->getRequests()[0],
            $service,
            null,
            null,
            null,
            null,
            $lat,
            $long,
            $radius,
            $limit
        );
        Expectation::assertQuery(
            $client->getRequests()[1],
            $service,
            null,
            null,
            null,
            null,
            $lat,
            $long,
            $radius,
            $limit
        );
    }
}
