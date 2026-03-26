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

namespace FastForward\Defer\ErrorReporter;

use Throwable;
use FastForward\Defer\ErrorReporterInterface;
use FastForward\Defer\EventDispatcher\Event\DeferredCallbackFailed;
use FastForward\Defer\Support\CallbackDescriber;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * This error reporter implementation MUST dispatch all reported exceptions as events using a PSR-14 compatible event dispatcher.
 * It SHALL provide a detailed event including the exception, callback description, and arguments.
 * If the dispatcher throws an exception, this class MUST log the failure using error_log and MUST NOT throw further exceptions.
 */
final readonly class PsrEventDispatcherErrorReporter implements ErrorReporterInterface
{
    /**
     * Constructs a new PsrEventDispatcherErrorReporter instance.
     *
     * @param EventDispatcherInterface $dispatcher the PSR-14 event dispatcher to use for error reporting
     */
    public function __construct(
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * Reports a throwable by dispatching a DeferredCallbackFailed event.
     *
     * This method MUST dispatch the exception as an event. If the dispatcher fails, the error MUST be logged.
     *
     * @param Throwable $throwable the exception or error to report
     * @param callable|null $callback the related callback, if available
     * @param array $args arguments passed to the callback, if any
     *
     * @return void
     */
    public function report(Throwable $throwable, ?callable $callback = null, array $args = []): void
    {
        try {
            $this->dispatcher->dispatch(
                new DeferredCallbackFailed(
                    throwable: $throwable,
                    callback: null !== $callback ? CallbackDescriber::describe($callback) : null,
                    arguments: $args,
                )
            );
        } catch (Throwable $reportingFailure) {
            error_log(
                \sprintf(
                    '[%s] Error reporter dispatch failed: %s: %s in %s:%d',
                    self::class,
                    $reportingFailure::class,
                    $reportingFailure->getMessage(),
                    $reportingFailure->getFile(),
                    $reportingFailure->getLine()
                )
            );
        }
    }
}
