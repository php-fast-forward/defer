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

use Prophecy\Prophecy\ObjectProphecy;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\Test;
use Prophecy\PhpUnit\ProphecyTrait;
use FastForward\Defer\EventDispatcher\ListenerProvider\DeferredCallbackListenerProvider;
use FastForward\Defer\EventDispatcher\Event\DeferredCallbackFailed;
use FastForward\Defer\EventDispatcher\Listener\LogDeferredCallbackFailure;
use Psr\Log\LoggerInterface;

#[CoversClass(DeferredCallbackListenerProvider::class)]
#[UsesClass(DeferredCallbackFailed::class)]
#[UsesClass(LogDeferredCallbackFailure::class)]
final class DeferredCallbackListenerProviderTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy $loggerProphecy;

    private DeferredCallbackListenerProvider $provider;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->loggerProphecy = $this->prophesize(LoggerInterface::class);
        $this->provider = new DeferredCallbackListenerProvider($this->loggerProphecy->reveal());
    }

    /**
     * @return void
     */
    #[Test]
    public function getListenersForDeferredCallbackFailedEventWillReturnCallableListener(): void
    {
        $event = new DeferredCallbackFailed(new Exception('fail'));
        $listeners = iterator_to_array($this->provider->getListenersForEvent($event));
        self::assertNotEmpty($listeners);
        self::assertIsCallable($listeners[0]);
    }

    /**
     * @return void
     */
    #[Test]
    public function getListenersForNonDeferredEventWillReturnEmpty(): void
    {
        $event = new class {};
        $listeners = iterator_to_array($this->provider->getListenersForEvent($event));
        self::assertEmpty($listeners);
    }
}
