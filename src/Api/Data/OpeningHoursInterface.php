<?php

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
