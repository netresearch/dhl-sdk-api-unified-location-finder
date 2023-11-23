<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Http;

use Dhl\Sdk\UnifiedLocationFinder\Api\LocationFinderServiceInterface;
use Dhl\Sdk\UnifiedLocationFinder\Api\ServiceFactoryInterface;
use Dhl\Sdk\UnifiedLocationFinder\Exception\ServiceExceptionFactory;
use Dhl\Sdk\UnifiedLocationFinder\Http\Client\ErrorPlugin;
use Dhl\Sdk\UnifiedLocationFinder\Model\LocationResponseMapper;
use Dhl\Sdk\UnifiedLocationFinder\Serializer\JsonSerializer;
use Dhl\Sdk\UnifiedLocationFinder\Service\LocationFinderService;
use Http\Client\Common\Plugin\HeaderSetPlugin;
use Http\Client\Common\Plugin\LoggerPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Exception\NotFoundException;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Formatter\FullHttpMessageFormatter;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

class HttpServiceFactory implements ServiceFactoryInterface
{
    public function __construct(private readonly ClientInterface $httpClient)
    {
    }

    public function createLocationFinderService(
        string $consumerKey,
        LoggerInterface $logger
    ): LocationFinderServiceInterface {
        $client = new PluginClient(
            $this->httpClient,
            [
                new HeaderSetPlugin(['Accept' => 'application/json', 'DHL-API-Key' => $consumerKey]),
                new LoggerPlugin($logger, new FullHttpMessageFormatter(null)),
                new ErrorPlugin(),
            ]
        );

        try {
            $requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        } catch (NotFoundException $exception) {
            throw ServiceExceptionFactory::create($exception);
        }

        return new LocationFinderService($client, $requestFactory, new JsonSerializer(), new LocationResponseMapper());
    }
}
