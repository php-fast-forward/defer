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

use Throwable;

/**
 * This interface MUST be implemented by any class that reports exceptions from deferred callbacks.
 * Implementations SHALL provide a report() method that handles the throwable, callback, and arguments.
 */
interface ErrorReporterInterface
{
    /**
     * Reports an exception from a deferred callback.
     *
     * This method MUST handle the throwable and MAY use the callback and arguments for context.
     *
     * @param Throwable $throwable the exception to report
     * @param callable|null $callback the related callback, if available
     * @param array $arguments arguments passed to the callback, if any
     *
     * @return void
     */
    public function report(Throwable $throwable, ?callable $callback = null, array $arguments = []): void;
}
