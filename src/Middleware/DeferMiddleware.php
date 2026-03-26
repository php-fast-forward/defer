<?php

declare(strict_types=1);

/**
 * This file is part of fast-forward/defer.
 *
 * This source file is subject to the license bundled
 * with this source code in the file LICENSE.
 *
 * @copyright Copyright (c) 2026 Felipe Sayão Lobato Abreu <github@mentordosnerds.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 *
 * @see       https://github.com/php-fast-forward/defer
 * @see       https://github.com/php-fast-forward
 * @see       https://datatracker.ietf.org/doc/html/rfc2119
 */

namespace FastForward\Defer\Middleware;

use LogicException;
use FastForward\Defer\Defer;
use FastForward\Defer\DeferInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This middleware MUST be used to inject and manage a Defer instance in a PSR-15 ServerRequest.
 * It SHALL ensure that all deferred callbacks are executed at the end of the request lifecycle.
 * The attribute name MAY be customized via the constructor.
 */
final readonly class DeferMiddleware implements MiddlewareInterface
{
    /**
     * Constructs a new DeferMiddleware instance.
     *
     * @param string $attribute the attribute name to use for storing the Defer instance (optional)
     */
    public function __construct(
        private string $attribute = DeferInterface::class,
    ) {}

    /**
     * Returns the attribute name used to store the Defer instance.
     *
     * @return string the attribute name
     */
    public function getAttribute(): string
    {
        return $this->attribute;
    }

    /**
     * Retrieves the Defer instance from the request.
     *
     * This method MUST throw a LogicException if the Defer instance is not found.
     *
     * @param ServerRequestInterface $request the request to retrieve the Defer instance from
     *
     * @return DeferInterface the Defer instance
     *
     * @throws LogicException
     */
    public function getDefer(ServerRequestInterface $request): DeferInterface
    {
        $defer = $request->getAttribute($this->attribute);

        if (! $defer instanceof DeferInterface) {
            throw new LogicException(\sprintf('Defer instance not found in request attribute "%s".', $this->attribute));
        }

        return $defer;
    }

    /**
     * Processes the request, injecting the Defer instance and ensuring execution at the end.
     *
     * @param ServerRequestInterface $request the incoming request
     * @param RequestHandlerInterface $handler the request handler
     *
     * @return ResponseInterface the response from the handler
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $defer = new Defer();

        $request = $request->withAttribute($this->attribute, $defer);

        try {
            return $handler->handle($request);
        } finally {
            unset($defer);
        }
    }
}
