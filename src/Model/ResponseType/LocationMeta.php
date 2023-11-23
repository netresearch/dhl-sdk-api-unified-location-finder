<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType;

class LocationMeta
{
    /**
     * @var \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\Id[]
     */
    private array $ids;

    private string $keyword = '';

    private int $keywordId;

    private string $type = '';

    /**
     * @return \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\Id[]
     */
    public function getIds(): array
    {
        return $this->ids;
    }

    public function getKeyword(): string
    {
        return $this->keyword;
    }

    public function getKeywordId(): int
    {
        return $this->keywordId;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
