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

namespace FastForward\Defer\EventDispatcher\Listener;

use FastForward\Defer\EventDispatcher\Event\DeferredCallbackFailed;
use Psr\Log\LoggerInterface;

/**
 * Listener that logs failed deferred callback executions using a PSR-3 compatible logger.
 *
 * This listener MUST be used to log all deferred callback failures. It SHALL log detailed information about the exception, callback, and arguments. This class MUST NOT throw exceptions during logging.
 */
final readonly class LogDeferredCallbackFailure
{
    /**
     * Constructs a new LogDeferredCallbackFailure listener.
     *
     * @param LoggerInterface $logger the PSR-3 logger to use for logging failures
     */
    public function __construct(
        private LoggerInterface $logger
    ) {}

    /**
     * Handles the DeferredCallbackFailed event by logging the failure.
     *
     * This method MUST log the exception details and callback information. It MUST NOT throw exceptions.
     *
     * @param DeferredCallbackFailed $event the event representing the callback failure
     *
     * @return void
     */
    public function __invoke(DeferredCallbackFailed $event): void
    {
        $this->logger->error(
            'Deferred callback failed: {exception_class}: {message}',
            [
                'exception_class' => $event->throwable::class,
                'message' => $event->throwable->getMessage(),
                'file' => $event->throwable->getFile(),
                'line' => $event->throwable->getLine(),
                'callback' => $event->callback,
                'callback_arguments' => $event->arguments,
                'exception' => $event->throwable,
            ]
        );
    }
}
