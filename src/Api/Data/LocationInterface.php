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

    public function getId(): string;

    public function getType(): string;

    public function getDistanceInMeter(): int;

    public function getName(): string;

    public function getNumber(): string;

    public function getGeo(): GeoInterface;

    public function getAddress(): AddressInterface;

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
