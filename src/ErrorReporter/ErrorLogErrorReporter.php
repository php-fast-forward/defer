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
use FastForward\Defer\Support\CallbackDescriber;

/**
 * This error reporter implementation MUST log all reported exceptions using error_log.
 * It SHALL provide a detailed log message including the exception class, message, file, line, and callback description.
 * This class MUST NOT throw exceptions during reporting.
 */
final class ErrorLogErrorReporter implements ErrorReporterInterface
{
    /**
     * Reports a throwable using error_log.
     *
     * This method MUST log the exception details and callback description. It MUST NOT throw exceptions.
     *
     * @param Throwable $throwable the exception or error to report
     * @param callable|null $callback the related callback, if available
     * @param array $args arguments passed to the callback, if any
     *
     * @return void
     */
    public function report(Throwable $throwable, ?callable $callback = null, array $args = []): void
    {
        error_log(
            \sprintf(
                '[%s] Deferred callback failed: %s: %s in %s:%d | callback=%s',
                self::class,
                $throwable::class,
                $throwable->getMessage(),
                $throwable->getFile(),
                $throwable->getLine(),
                null !== $callback ? CallbackDescriber::describe($callback) : 'unknown'
            )
        );
    }
}
