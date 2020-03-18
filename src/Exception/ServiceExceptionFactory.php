<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\UnifiedLocationFinder\Exception;

use Http\Client\Exception as HttpClientException;

class ServiceExceptionFactory
{
    /**
     * Create a service exception.
     *
     * @param \Throwable $exception
     * @return ServiceException
     */
    public static function create(\Throwable $exception): ServiceException
    {
        return new ServiceException($exception->getMessage(), $exception->getCode(), $exception);
    }

    /**
     * Create a service exception from HTTP client exception.
     *
     * @param HttpClientException $exception
     * @return ServiceException
     */
    public static function createServiceException(HttpClientException $exception): ServiceException
    {
        if (!$exception instanceof \Throwable) {
            return new ServiceException('Unknown exception occurred', 0);
        }

        return self::create($exception);
    }

    /**
     * Create a detailed service exception.
     *
     * @param DetailedErrorException $exception
     * @return DetailedServiceException
     */
    public static function createDetailedServiceException(DetailedErrorException $exception): DetailedServiceException
    {
        return new DetailedServiceException($exception->getMessage(), $exception->getCode(), $exception);
    }

    /**
     * Create an authentication exception.
     *
     * @param AuthenticationErrorException $exception
     * @return AuthenticationException
     */
    public static function createAuthenticationException(
        AuthenticationErrorException $exception
    ): AuthenticationException {
        return new AuthenticationException($exception->getMessage(), $exception->getCode(), $exception);
    }
}
