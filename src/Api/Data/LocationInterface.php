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
    public const TYPE_SERVICEPOINT = 'servicepoint';
    public const TYPE_LOCKER = 'locker';
    public const TYPE_POSTOFFICE = 'postoffice';
    public const TYPE_POSTBANK = 'postbank';

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
     * @return string
     */
    public function getPlace(): string;

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
