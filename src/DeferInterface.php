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

namespace FastForward\Defer;

use Countable;

/**
 * This interface MUST be implemented by any class that manages deferred callbacks.
 * Implementations SHALL provide mechanisms to register, execute, and check the presence of callbacks.
 * All documentation MUST be in English and use RFC 2119 terminology.
 */
interface DeferInterface extends Countable
{
    /**
     * Registers a callback to be executed later.
     *
     * This method MUST add the callback to the stack. It MAY be called multiple times.
     *
     * @param callable $callback the callback to register
     * @param mixed ...$arguments Arguments for the callback.
     *
     * @return void
     */
    public function __invoke(callable $callback, mixed ...$arguments): void;

    /**
     * Registers a callback to be executed later.
     *
     * This method MUST add the callback to the stack. It MAY be called multiple times.
     *
     * @param callable $callback the callback to register
     * @param mixed ...$args Arguments for the callback.
     *
     * @return void
     */
    public function defer(callable $callback, mixed ...$args): void;

    /**
     * Determines if there are no registered callbacks.
     *
     * This method MUST return true if no callbacks are registered; otherwise, it MUST return false.
     *
     * @return bool true if no callbacks are registered; otherwise, false
     */
    public function isEmpty(): bool;
}
