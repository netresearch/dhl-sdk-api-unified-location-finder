<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType;

class OpeningHoursSpecification
{
    /**
     * @var string
     */
    private $dayOfWeek;

    /**
     * @var string
     */
    private $opens;

    /**
     * @var string
     */
    private $closes;

    /**
     * @return string
     */
    public function getDayOfWeek(): string
    {
        return $this->dayOfWeek;
    }

    /**
     * @return string
     */
    public function getOpens(): string
    {
        return $this->opens;
    }

    /**
     * @return string
     */
    public function getCloses(): string
    {
        return $this->closes;
    }
}
