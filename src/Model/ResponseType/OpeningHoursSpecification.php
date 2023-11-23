<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType;

class OpeningHoursSpecification
{
    private string $dayOfWeek = '';

    private string $opens = '';

    private string $closes = '';

    public function getDayOfWeek(): string
    {
        return $this->dayOfWeek;
    }

    public function getOpens(): string
    {
        return $this->opens;
    }

    public function getCloses(): string
    {
        return $this->closes;
    }
}
