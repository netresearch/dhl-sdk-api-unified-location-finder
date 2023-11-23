<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Service\LocationFinderService;

use Dhl\Sdk\UnifiedLocationFinder\Api\Data\OpeningHoursInterface;

class OpeningHours implements OpeningHoursInterface
{
    public function __construct(
        private readonly string $dayOfWeek,
        private readonly string $closes,
        private readonly string $opens,
        private readonly string $validFrom,
        private readonly string $validTo
    ) {
    }

    public function getDayOfWeek(): string
    {
        return $this->dayOfWeek;
    }

    public function getCloses(): string
    {
        return $this->closes;
    }

    public function getOpens(): string
    {
        return $this->opens;
    }

    public function getValidFrom(): string
    {
        return $this->validFrom;
    }

    public function getValidTo(): string
    {
        return $this->validTo;
    }
}
