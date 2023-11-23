<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Api\Data;

/**
 * @api
 */
interface OpeningHoursInterface
{
    public function getDayOfWeek(): string;

    public function getCloses(): string;

    public function getOpens(): string;

    public function getValidFrom(): string;

    public function getValidTo(): string;
}
