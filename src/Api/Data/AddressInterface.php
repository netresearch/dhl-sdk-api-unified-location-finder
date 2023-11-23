<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Api\Data;

/**
 * @api
 */
interface AddressInterface
{
    public function getCountryCode(): string;

    public function getPostalCode(): string;

    public function getCity(): string;

    public function getStreet(): string;
}
