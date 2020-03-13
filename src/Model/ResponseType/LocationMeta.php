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
    private $ids;

    /**
     * @var string
     */
    private $keyword;

    /**
     * @var int
     */
    private $keywordId;

    /**
     * @var string
     */
    private $type;

    /**
     * @return \Dhl\Sdk\UnifiedLocationFinder\Model\ResponseType\Id[]
     */
    public function getIds(): array
    {
        return $this->ids;
    }

    /**
     * @return string
     */
    public function getKeyword(): string
    {
        return $this->keyword;
    }

    /**
     * @return int
     */
    public function getKeywordId(): int
    {
        return $this->keywordId;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
