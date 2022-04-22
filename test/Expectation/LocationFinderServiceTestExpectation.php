<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Test\Expectation;

use Dhl\Sdk\UnifiedLocationFinder\Api\Data\LocationInterface;
use Dhl\Sdk\UnifiedLocationFinder\Api\LocationFinderServiceInterface;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\RequestInterface;
use Psr\Log\Test\TestLogger;

class LocationFinderServiceTestExpectation
{
    /**
     * Assert method arguments were passed properly to request query parameters.
     *
     * @param RequestInterface $request
     * @param string|null $service
     * @param string|null $countryCode
     * @param string|null $postalCode
     * @param string|null $city
     * @param string|null $street
     * @param float|null $latitude
     * @param float|null $longitude
     * @param int|null $radius
     * @param int|null $limit
     */
    public static function assertQuery(
        RequestInterface $request,
        string $service = null,
        string $countryCode = null,
        string $postalCode = null,
        string $city = null,
        string $street = null,
        float $latitude = null,
        float $longitude = null,
        int $radius = null,
        int $limit = null
    ): void {
        $requestParams = [
            'countryCode' => $countryCode,
            'addressLocality' => $city,
            'postalCode' => $postalCode,
            'streetAddress' => $street,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'radius' => $radius,
            'limit' => $limit,
        ];

        $requestParams = array_filter($requestParams);

        $query = $request->getUri()->getQuery();
        $queryParts = explode('&', $query);
        $queryParams = [];
        foreach ($queryParts as $queryPart) {
            list($key, $value) = explode('=', $queryPart);
            $queryParams[$key] = $value;
        }

        Assert::assertSame(array_intersect_assoc($requestParams, $queryParams), $requestParams);
        if ($service) {
            $serviceType = ($service === LocationFinderServiceInterface::SERVICE_PARCEL) ? 'parcel' : 'express';
            Assert::assertNotFalse(strpos($query, "serviceType={$serviceType}"));
        }
    }

    /**
     * Assert that there was an error logged for error responses
     *
     * @param \Throwable $exception
     * @param TestLogger $logger
     */
    public static function assertExceptionLogged(\Throwable $exception, TestLogger $logger): void
    {
        Assert::assertTrue($logger->hasErrorRecords(), 'No error logged');
        Assert::assertTrue($logger->hasErrorThatContains($exception->getMessage()), 'Error message not logged');
    }

    /**
     * Assert that error communication was logged
     *
     * @param string $responseJson
     * @param RequestInterface $request
     * @param TestLogger $logger
     */
    public static function assertErrorLogged(string $responseJson, RequestInterface $request, TestLogger $logger): void
    {
        $statusRegex = '|^HTTP/\d\.\d\s\d{3}\s[\w\s]+$|m';

        $hasRequest = $logger->hasInfoThatContains($request->getUri()->getQuery());
        $hasResponseStatus = $logger->hasErrorThatMatches($statusRegex);
        $hasResponse = $logger->hasErrorThatContains($responseJson);

        Assert::assertTrue($hasRequest, 'Logged messages do not contain request.');
        Assert::assertTrue($hasResponseStatus, 'Logged messages do not contain response status code.');
        Assert::assertTrue($hasResponse, 'Logged messages do not contain response.');
    }

    /**
     * Assert that successful communication was logged
     *
     * @param string $responseJson
     * @param RequestInterface $request
     * @param TestLogger $logger
     */
    public static function assertCommunicationLogged(
        string $responseJson,
        RequestInterface $request,
        TestLogger $logger
    ): void {
        $statusRegex = '|^HTTP/\d\.\d\s\d{3}\s[\w\s]+$|m';

        $hasRequest = $logger->hasInfoThatContains($request->getUri()->getQuery());
        $hasResponseStatus = $logger->hasInfoThatMatches($statusRegex);
        $hasResponse = $logger->hasInfoThatContains($responseJson);

        Assert::assertTrue($hasRequest, 'Logged messages do not contain request.');
        Assert::assertTrue($hasResponseStatus, 'Logged messages do not contain response status code.');
        Assert::assertTrue($hasResponse, 'Logged messages do not contain response.');
    }

    /**
     * Assert that all response objects have been converted and the data is in the correct places
     *
     * @param string $jsonResponse
     * @param LocationInterface[] $result
     */
    public static function assertLocationsMapped(string $jsonResponse, array $result): void
    {
        $response = json_decode($jsonResponse, false);
        foreach ($response->locations as $key => $apiLocation) {
            $location = $result[$key];
            Assert::assertEquals($apiLocation->location->ids[0]->locationId, $location->getId());
            Assert::assertEquals($apiLocation->place->address->postalCode, $location->getAddress()->getPostalCode());
            Assert::assertEquals($apiLocation->place->address->countryCode, $location->getAddress()->getCountryCode());
            Assert::assertEquals($apiLocation->place->address->addressLocality, $location->getAddress()->getCity());
            Assert::assertEquals($apiLocation->place->address->streetAddress, $location->getAddress()->getStreet());
            Assert::assertEquals($apiLocation->name, $location->getName());
            Assert::assertEquals($apiLocation->location->type, $location->getType());
            Assert::assertEquals($apiLocation->location->keywordId, $location->getNumber());
            Assert::assertEquals($apiLocation->distance, $location->getDistanceInMeter());
            Assert::assertEquals($apiLocation->place->geo->latitude, $location->getGeo()->getLat());
            Assert::assertEquals($apiLocation->place->geo->longitude, $location->getGeo()->getLong());
            Assert::assertEquals($apiLocation->serviceTypes, $location->getServices());

            foreach ($apiLocation->openingHours as $index => $openingHour) {
                Assert::assertEquals($openingHour->opens, $location->getOpeningHours()[$index]->getOpens());
                Assert::assertEquals($openingHour->closes, $location->getOpeningHours()[$index]->getCloses());
                Assert::assertEquals($openingHour->dayOfWeek, $location->getOpeningHours()[$index]->getDayOfWeek());
            }

            foreach ($apiLocation->closurePeriods as $index => $closurePeriod) {
                Assert::assertEquals(
                    $closurePeriod->fromDate,
                    $location->getSpecialOpeningHours()[$index]->getValidFrom()
                );
                Assert::assertEquals(
                    $closurePeriod->toDate,
                    $location->getSpecialOpeningHours()[$index]->getValidTo()
                );
            }
        }
    }
}
