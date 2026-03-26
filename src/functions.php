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

/**
 * Creates a new Defer instance, optionally registering a callback immediately.
 *
 * This function MUST return a DeferInterface implementation. If a callback is provided, it SHALL be registered.
 *
 * @param callable|null $callback the callback to register (optional)
 * @param mixed ...$arguments Arguments for the callback.
 *
 * @return DeferInterface the Defer instance
 */
function defer(?callable $callback = null, mixed ...$arguments): DeferInterface
{
    return new Defer($callback, ...$arguments);
}

/**
 * Executes a callback within a controlled scope, ensuring resources are released at the end.
 *
 * This function MUST create a Defer instance, pass it to the factory, and execute the callback.
 * The Defer instance SHALL be unset after execution.
 *
 * @param callable $factory a function that receives the Defer and returns the resource
 * @param callable $callback a function that receives the resource and executes the desired logic
 *
 * @return mixed the return value of the callback
 */
function using(callable $factory, callable $callback): mixed
{
    $defer = defer();

    try {
        return $callback($factory($defer));
    } finally {
        unset($defer);
    }
}

/**
 * Executes a callback within a deferred scope, ensuring all registered callbacks are executed at the end.
 *
 * This function MUST create a Defer instance, pass it to the callback, and unset it after execution.
 *
 * @param callable $callback a function that receives the Defer and executes the desired logic
 *
 * @return mixed the return value of the callback
 */
function scope(callable $callback): mixed
{
    $defer = defer();

    try {
        return $callback($defer);
    } finally {
        unset($defer);
    }
}
