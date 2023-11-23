<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Service;

use Dhl\Sdk\UnifiedLocationFinder\Api\LocationFinderServiceInterface;
use Dhl\Sdk\UnifiedLocationFinder\Api\ServiceFactoryInterface;
use Dhl\Sdk\UnifiedLocationFinder\Exception\ServiceExceptionFactory;
use Dhl\Sdk\UnifiedLocationFinder\Http\HttpServiceFactory;
use Http\Discovery\Exception\NotFoundException;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Log\LoggerInterface;

class ServiceFactory implements ServiceFactoryInterface
{
    public function createLocationFinderService(
        string $consumerKey,
        LoggerInterface $logger
    ): LocationFinderServiceInterface {
        try {
            $httpClient = Psr18ClientDiscovery::find();
        } catch (NotFoundException $exception) {
            throw ServiceExceptionFactory::create($exception);
        }

        $httpFactory = new HttpServiceFactory($httpClient);

        return $httpFactory->createLocationFinderService($consumerKey, $logger);
    }
}
