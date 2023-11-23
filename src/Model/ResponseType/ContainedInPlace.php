<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType;

class ContainedInPlace
{
    private string $name = '';

    public function getName(): string
    {
        return $this->name;
    }
}
