<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Model;

use Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\ClosurePeriod;
use Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\Id;
use Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\Location as ApiLocation;
use Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\OpeningHoursSpecification;
use Dhl\Sdk\UnifiedLocationFinder\Service\LocationFinderService\Address;
use Dhl\Sdk\UnifiedLocationFinder\Service\LocationFinderService\Geo;
use Dhl\Sdk\UnifiedLocationFinder\Service\LocationFinderService\Location;
use Dhl\Sdk\UnifiedLocationFinder\Service\LocationFinderService\OpeningHours;

class LocationResponseMapper
{
    /**
     * @param ApiLocation[] $apiLocations
     * @return Location[]
     */
    public function map(array $apiLocations): array
    {
        $locations = [];

        foreach ($apiLocations as $apiLocation) {
            $ids = $apiLocation->getLocation()->getIds();
            $id = array_shift($ids);
            $place = $apiLocation->getPlace();

            $address = new Address(
                $place->getAddress()->getCountryCode(),
                $place->getAddress()->getPostalCode(),
                $place->getAddress()->getAddressLocality(),
                $place->getAddress()->getStreetAddress()
            );
            $geo = new Geo((float) $place->getGeo()->getLongitude(), (float) $place->getGeo()->getLatitude());

            $openingHours = array_map(
                fn(OpeningHoursSpecification $openingHour): OpeningHours => new OpeningHours(
                    $openingHour->getDayOfWeek(),
                    $openingHour->getCloses(),
                    $openingHour->getOpens(),
                    '',
                    ''
                ),
                $apiLocation->getOpeningHours()
            );

            $closurePeriods = array_map(
                fn(ClosurePeriod $closurePeriod): OpeningHours => new OpeningHours(
                    '',
                    '00:00:00',
                    '00:00:00',
                    $closurePeriod->getFromDate(),
                    $closurePeriod->getToDate()
                ),
                $apiLocation->getClosurePeriods()
            );

            $location = new Location(
                $id instanceof Id ? $id->getLocationId() : $apiLocation->getUrl(),
                $apiLocation->getLocation()->getType(),
                $apiLocation->getDistance(),
                $apiLocation->getName(),
                (string) $apiLocation->getLocation()->getKeywordId(),
                $geo,
                $address,
                $place->getContainedInPlace() !== null ? $place->getContainedInPlace()->getName() : '',
                $openingHours,
                $closurePeriods,
                $apiLocation->getServiceTypes()
            );

            $locations[] = $location;
        }

        return $locations;
    }
}
