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
use Http\Discovery\MessageFactoryDiscovery;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

class LocationFinderServiceTest extends TestCase
{
    /**
     * @return TransferException[][]
     */
    public function exceptionProvider(): array
    {
        $messageFactory = MessageFactoryDiscovery::find();

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
    public function errorDataProvider(): array
    {
        return LocationResponse::getErrorResponse();
    }

    /**
     * @return string[][]
     */
    public function parcelPickupLocationsProvider(): array
    {
        return LocationResponse::getParcelPickupLocationsResponse();
    }

    /**
     * @return string[][]
     */
    public function parcelDropOffLocationsProvider(): array
    {
        return LocationResponse::getParcelDropOffLocationsResponse();
    }

    /**
     * @return string[][]
     */
    public function expressPickupLocationsProvider(): array
    {
        return LocationResponse::getExpressPickupLocationsResponse();
    }

    /**
     * @return string[][]
     */
    public function expressDropOffLocationsProvider(): array
    {
        return LocationResponse::getExpressDropOffLocationsResponse();
    }

    /**
     * @return string[][]
     */
    public function geoPickupLocationsProvider(): array
    {
        return LocationResponse::getParcelPickupLocationsByGeoResponse();
    }

    /**
     * @return string[][]
     */
    public function geoDropOffLocationsProvider(): array
    {
        return LocationResponse::getDropOffLocationsByGeoResponse();
    }

    /**
     * Assert that HTTP client exceptions with no JSON response are transformed into service exceptions.
     *
     * @test
     * @dataProvider exceptionProvider
     *
     * @param \Exception $exception
     * @throws ServiceException
     */
    public function handleExceptions(\Exception $exception)
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
     * @test
     * @dataProvider errorDataProvider
     *
     * @param string $jsonResponse
     * @throws ServiceException
     */
    public function handleErrors(string $jsonResponse)
    {
        $response = json_decode($jsonResponse, true);

        if ($response['status'] === 401) {
            $this->expectException(AuthenticationException::class);
        } else {
            $this->expectException(DetailedServiceException::class);
        }

        $this->expectExceptionCode($response['status']);
        $this->expectExceptionMessageRegExp("#{$response['title']}#");

        $logger = new TestLogger();
        $client = new Client();
        $messageFactory = MessageFactoryDiscovery::find();
        $httpResponse = $messageFactory->createResponse(
            $response['status'],
            $response['title'],
            ['Content-Type' => 'application/json',],
            $jsonResponse
        );

        $client->setDefaultResponse($httpResponse);

        $serviceFactory = new HttpServiceFactory($client);
        $service = $serviceFactory->createLocationFinderService('FOO_KEY', $logger);

        try {
            $service->getPickUpLocations('DE');
        } catch (ServiceException $exception) {
            Expectation::assertErrorLogged($jsonResponse, $client->getLastRequest(), $logger);

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
     * @test
     * @dataProvider parcelPickupLocationsProvider
     *
     * @param string $lockersResponse
     * @param string $postOfficesResponse
     * @throws ServiceException
     */
    public function findParcelPickupLocations(string $lockersResponse, string $postOfficesResponse)
    {
        $messageFactory = MessageFactoryDiscovery::find();

        $logger = new TestLogger();
        $client = new Client();
        $client->addResponse(
            $messageFactory->createResponse(200, 'OK', ['Content-Type' => 'application/json'], $lockersResponse)
        );
        $client->addResponse(
            $messageFactory->createResponse(200, 'OK', ['Content-Type' => 'application/json'], $postOfficesResponse)
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
        Expectation::assertCommunicationLogged($lockersResponse, $client->getRequests()[0], $logger);
        Expectation::assertCommunicationLogged($postOfficesResponse, $client->getRequests()[1], $logger);

        $locations = array_merge(
            json_decode($lockersResponse, true)['locations'],
            json_decode($postOfficesResponse, true)['locations']
        );

        Expectation::assertLocationsMapped(json_encode(['locations' => $locations]), $result);
    }

    /**
     * Scenario: query pickup locations for DHL Express carrier.
     *
     * - Assert arguments being passed to web service
     * - Assert request and response being logged
     * - Assert web service response being properly transformed to SDK response objects.
     *
     * @test
     * @dataProvider expressPickupLocationsProvider
     *
     * @param string $jsonResponse
     * @throws ServiceException
     */
    public function findExpressPickupLocations(string $jsonResponse)
    {
        $messageFactory = MessageFactoryDiscovery::find();
        $response = $messageFactory->createResponse(200, 'OK', ['Content-Type' => 'application/json'], $jsonResponse);

        $logger = new TestLogger();
        $client = new Client();
        $client->addResponse($response);

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
     * @test
     * @dataProvider parcelDropOffLocationsProvider
     *
     * @param string $postOfficesResponse
     * @param string $lockersResponse
     * @throws ServiceException
     */
    public function findParcelDropOffLocations(string $postOfficesResponse, string $lockersResponse)
    {
        $messageFactory = MessageFactoryDiscovery::find();

        $logger = new TestLogger();
        $client = new Client();
        $client->addResponse(
            $messageFactory->createResponse(200, 'OK', ['Content-Type' => 'application/json'], $lockersResponse)
        );
        $client->addResponse(
            $messageFactory->createResponse(200, 'OK', ['Content-Type' => 'application/json'], $postOfficesResponse)
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
            json_decode($lockersResponse, true)['locations'],
            json_decode($postOfficesResponse, true)['locations']
        );

        Expectation::assertLocationsMapped(json_encode(['locations' => $locations]), $result);
    }

    /**
     * Scenario: query drop-off locations for DHL Express carrier.
     *
     * - Assert arguments being passed to web service
     * - Assert request and response being logged
     * - Assert web service response being properly transformed to SDK response objects.
     *
     * @test
     * @dataProvider expressDropOffLocationsProvider
     *
     * @param string $jsonResponse
     * @throws ServiceException
     */
    public function findExpressDropOffLocations(string $jsonResponse)
    {
        $messageFactory = MessageFactoryDiscovery::find();
        $response = $messageFactory->createResponse(200, 'OK', ['Content-Type' => 'application/json'], $jsonResponse);

        $logger = new TestLogger();
        $client = new Client();
        $client->addResponse($response);

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
     * @test
     * @dataProvider geoPickupLocationsProvider
     *
     * @param string $lockersResponse
     * @param string $postOfficesResponse
     * @throws ServiceException
     */
    public function findPickUpLocationsByGeo(string $lockersResponse, string $postOfficesResponse)
    {
        $messageFactory = MessageFactoryDiscovery::find();
        $client = new Client();
        $logger = new TestLogger();

        $client->addResponse(
            $messageFactory->createResponse(200, 'OK', ['Content-Type' => 'application/json'], $lockersResponse)
        );
        $client->addResponse(
            $messageFactory->createResponse(200, 'OK', ['Content-Type' => 'application/json'], $postOfficesResponse)
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

    /**
     * Scenario: query drop-off locations for DHL Paket carrier by geo coordinates.
     *
     * - Assert arguments being passed to web service
     *
     * @test
     * @dataProvider geoDropOffLocationsProvider
     *
     * @param string $lockersResponse
     * @param string $postOfficesResponse
     * @throws ServiceException
     */
    public function findDropOffLocationsByGeo(string $lockersResponse, string $postOfficesResponse)
    {
        $messageFactory = MessageFactoryDiscovery::find();
        $client = new Client();
        $logger = new TestLogger();

        $client->addResponse(
            $messageFactory->createResponse(200, 'OK', ['Content-Type' => 'application/json'], $lockersResponse)
        );
        $client->addResponse(
            $messageFactory->createResponse(200, 'OK', ['Content-Type' => 'application/json'], $postOfficesResponse)
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
