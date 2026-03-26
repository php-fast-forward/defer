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

namespace FastForward\Defer\EventDispatcher\ListenerProvider;

use FastForward\Defer\EventDispatcher\Event\DeferredCallbackFailed;
use FastForward\Defer\EventDispatcher\Listener\LogDeferredCallbackFailure;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * This provider MUST be used to supply listeners for DeferredCallbackFailed events.
 * It SHALL provide a LogDeferredCallbackFailure listener for each such event.
 * The logger MAY be customized via setLogger().
 */
final class DeferredCallbackListenerProvider implements ListenerProviderInterface, LoggerAwareInterface
{
    /**
     * Constructs a new DeferredCallbackListenerProvider instance.
     *
     * @param LoggerInterface|null $logger the logger to use for listeners (optional)
     */
    public function __construct(
        private ?LoggerInterface $logger = new NullLogger()
    ) {}

    /**
     * Sets the logger to be used by listeners.
     *
     * @param LoggerInterface $logger the logger to set
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * Returns listeners for the given event.
     *
     * This method MUST yield a LogDeferredCallbackFailure listener for DeferredCallbackFailed events.
     *
     * @param object $event the event to get listeners for
     *
     * @return iterable the listeners for the event
     */
    public function getListenersForEvent(object $event): iterable
    {
        if (! $event instanceof DeferredCallbackFailed) {
            return;
        }

        yield new LogDeferredCallbackFailure($this->logger);
    }
}
