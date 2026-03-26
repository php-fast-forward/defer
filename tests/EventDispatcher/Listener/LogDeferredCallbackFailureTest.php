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
use FastForward\Defer\EventDispatcher\Listener\LogDeferredCallbackFailure;
use FastForward\Defer\EventDispatcher\Event\DeferredCallbackFailed;
use Psr\Log\LoggerInterface;

#[CoversClass(LogDeferredCallbackFailure::class)]
#[UsesClass(DeferredCallbackFailed::class)]
final class LogDeferredCallbackFailureTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy $loggerProphecy;

    private LogDeferredCallbackFailure $listener;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->loggerProphecy = $this->prophesize(LoggerInterface::class);
        $this->listener = new LogDeferredCallbackFailure($this->loggerProphecy->reveal());
    }

    /**
     * @return void
     */
    #[Test]
    public function invokeWithDeferredCallbackFailedEventWillLogError(): void
    {
        $throwable = new Exception('fail');
        $event = new DeferredCallbackFailed($throwable, 'cb', ['x']);

        $this->loggerProphecy->error(
            'Deferred callback failed: {exception_class}: {message}',
            [
                'exception_class' => 'Exception',
                'message' => 'fail',
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'callback' => 'cb',
                'callback_arguments' => ['x'],
                'exception' => $throwable,
            ]
        )->shouldBeCalled();

        ($this->listener)($event);
    }
}
