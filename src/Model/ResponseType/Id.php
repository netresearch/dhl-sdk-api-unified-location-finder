<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType;

class Id
{
    private string $locationId = '';

    private string $provider = '';

    public function getLocationId(): string
    {
        return $this->locationId;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }
}
