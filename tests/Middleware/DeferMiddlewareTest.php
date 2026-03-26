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

use FastForward\Defer\Defer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\Test;
use Prophecy\PhpUnit\ProphecyTrait;
use FastForward\Defer\Middleware\DeferMiddleware;
use FastForward\Defer\DeferInterface;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

#[CoversClass(DeferMiddleware::class)]
#[UsesClass(Defer::class)]
final class DeferMiddlewareTest extends TestCase
{
    use ProphecyTrait;

    private string $attribute;

    private ObjectProphecy $requestProphecy;

    private ObjectProphecy $handlerProphecy;

    private ObjectProphecy $deferProphecy;

    private DeferMiddleware $middleware;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->attribute = uniqid('defer', true);

        $this->requestProphecy = $this->prophesize(ServerRequestInterface::class);
        $this->handlerProphecy = $this->prophesize(RequestHandlerInterface::class);
        $this->deferProphecy = $this->prophesize(DeferInterface::class);

        $this->requestProphecy->withAttribute($this->attribute, Argument::type(DeferInterface::class))
            ->willReturn($this->requestProphecy->reveal());
        $this->requestProphecy->getAttribute($this->attribute)
            ->willReturn($this->deferProphecy->reveal());

        $this->handlerProphecy->handle($this->requestProphecy->reveal())
            ->willReturn($this->prophesize(ResponseInterface::class)->reveal());

        $this->middleware = new DeferMiddleware($this->attribute);
    }

    /**
     * @return void
     */
    #[Test]
    public function processWithValidInputWillCallHandler(): void
    {
        self::assertInstanceOf(
            ResponseInterface::class,
            $this->middleware->process($this->requestProphecy->reveal(), $this->handlerProphecy->reveal()),
        );
    }

    /**
     * @return void
     */
    #[Test]
    public function getDeferWithDeferAttributeWillReturnDeferInstance(): void
    {
        self::assertSame($this->deferProphecy->reveal(), $this->middleware->getDefer($this->requestProphecy->reveal()));
    }

    /**
     * @return void
     */
    #[Test]
    public function getDeferWithMissingAttributeWillThrow(): void
    {
        $this->expectException(LogicException::class);

        $this->requestProphecy->getAttribute($this->attribute)
            ->willReturn(null);

        $this->middleware->getDefer($this->requestProphecy->reveal());
    }
}
