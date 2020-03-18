<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Api\Data;

/**
 * @api
 */
interface LocationInterface
{
    const TYPE_SERVICEPOINT = 'servicepoint';
    const TYPE_LOCKER = 'locker';
    const TYPE_POSTOFFICE = 'postoffice';
    const TYPE_POSTBANK = 'postbank';

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return int
     */
    public function getDistanceInMeter(): int;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getNumber(): string;

    /**
     * @return GeoInterface
     */
    public function getGeo(): GeoInterface;

    /**
     * @return AddressInterface
     */
    public function getAddress(): AddressInterface;

    /**
     * @return OpeningHoursInterface[]
     */
    public function getOpeningHours(): array;

    /**
     * @return OpeningHoursInterface[]
     */
    public function getSpecialOpeningHours(): array;

    /**
     * @return string[]
     */
    public function getServices(): array;
}
