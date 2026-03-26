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

use Psr\Http\Message\ServerRequestInterface;
use FastForward\Defer\DeferInterface;
use FastForward\Defer\Middleware\DeferMiddleware;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

require dirname(__DIR__) . '/vendor/autoload.php';

$middleware = new DeferMiddleware();

$request = new ServerRequest('GET', '/');

$handler = new class implements RequestHandlerInterface {
    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $defer = $request->getAttribute(DeferInterface::class);

        $defer(static fn(): int => print "request cleanup 1\n");
        $defer(static fn(): int => print "request cleanup 2\n");

        echo "handling request\n";

        return new Response(200, [], 'ok');
    }
};

$response = $middleware->process($request, $handler);

echo $response->getStatusCode() . \PHP_EOL;
