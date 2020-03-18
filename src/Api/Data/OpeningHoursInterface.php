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
    /**
     * @return string
     */
    public function getDayOfWeek(): string;

    /**
     * @return string
     */
    public function getCloses(): string;

    /**
     * @return string
     */
    public function getOpens(): string;

    /**
     * @return string
     */
    public function getValidFrom(): string;

    /**
     * @return string
     */
    public function getValidTo(): string;
}
