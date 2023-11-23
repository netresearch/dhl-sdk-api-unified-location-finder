<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Api;

use Dhl\Sdk\UnifiedLocationFinder\Exception\ServiceException;
use Psr\Log\LoggerInterface;

/**
 * @api
 */
interface ServiceFactoryInterface
{
    /**
     * @throws ServiceException
     */
    public function createLocationFinderService(
        string $consumerKey,
        LoggerInterface $logger
    ): LocationFinderServiceInterface;
}
