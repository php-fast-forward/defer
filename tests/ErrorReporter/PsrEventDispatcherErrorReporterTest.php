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
use FastForward\Defer\EventDispatcher\Event\DeferredCallbackFailed;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\Test;
use FastForward\Defer\ErrorReporter\PsrEventDispatcherErrorReporter;
use FastForward\Defer\Support\CallbackDescriber;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;

#[CoversClass(PsrEventDispatcherErrorReporter::class)]
#[UsesClass(DeferredCallbackFailed::class)]
#[UsesClass(CallbackDescriber::class)]
final class PsrEventDispatcherErrorReporterTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy $dispatcher;

    private PsrEventDispatcherErrorReporter $reporter;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->dispatcher = $this->prophesize(EventDispatcherInterface::class);
        $this->reporter = new PsrEventDispatcherErrorReporter($this->dispatcher->reveal());
    }

    /**
     * @return void
     */
    #[Test]
    public function reportWithThrowableWillDispatchEvent(): void
    {
        $throwable = new Exception('fail');

        $this->dispatcher->dispatch(
            Argument::that(
                fn($event): bool => $event instanceof DeferredCallbackFailed && $event->throwable === $throwable
            )
        )->shouldBeCalled();

        $this->reporter->report($throwable, fn(): null => null);
    }

    /**
     * @return void
     */
    #[Test]
    public function reportWithDispatcherFailureWillLogError(): void
    {
        $throwable = new Exception('fail');
        $dispatcherException = new Exception('dispatcher failed');

        $this->dispatcher->dispatch(Argument::type(DeferredCallbackFailed::class))
            ->willThrow($dispatcherException)
            ->shouldBeCalled();

        $reporter = $this->reporter;

        // Intercepta error_log usando um stream temporário
        $logFile = tempnam(sys_get_temp_dir(), 'log');
        $originalErrorLog = ini_set('error_log', $logFile);

        $reporter->report($throwable, fn(): null => null);

        ini_set('error_log', $originalErrorLog ?: '');
        $logContent = file_get_contents($logFile);
        unlink($logFile);

        self::assertStringContainsString('Error reporter dispatch failed', $logContent);
        self::assertStringContainsString('dispatcher failed', $logContent);
    }
}
