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

namespace FastForward\Defer\EventDispatcher\Event;

use Throwable;

/**
 * Event representing a failed deferred callback execution.
 *
 * This event MUST be dispatched whenever a deferred callback throws an exception.
 * It SHALL encapsulate the throwable, the callback description, and the callback arguments.
 * Consumers of this event MUST NOT modify its properties.
 */
readonly class DeferredCallbackFailed
{
    /**
     * Constructs a new DeferredCallbackFailed event.
     *
     * @param Throwable $throwable the exception thrown by the callback
     * @param string|null $callback the description of the callback, if available
     * @param array $arguments the arguments passed to the callback
     */
    public function __construct(
        public Throwable $throwable,
        public ?string $callback = null,
        public array $arguments = [],
    ) {}
}
