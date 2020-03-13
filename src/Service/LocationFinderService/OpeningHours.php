<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Service\LocationFinderService;

use Dhl\Sdk\UnifiedLocationFinder\Api\Data\OpeningHoursInterface;

class OpeningHours implements OpeningHoursInterface
{
    /**
     * @var string
     */
    private $dayOfWeek;

    /**
     * @var string
     */
    private $closes;

    /**
     * @var string
     */
    private $opens;

    /**
     * @var string
     */
    private $validFrom;

    /**
     * @var string
     */
    private $validTo;

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
