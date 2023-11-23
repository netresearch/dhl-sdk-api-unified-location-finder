<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType;

class ClosurePeriod
{
    private string $type = '';

    private string $fromDate = '';

    private string $toDate = '';

    public function getType(): string
    {
        return $this->type;
    }

    public function getFromDate(): string
    {
        return $this->fromDate;
    }

    public function getToDate(): string
    {
        return $this->toDate;
    }
}
