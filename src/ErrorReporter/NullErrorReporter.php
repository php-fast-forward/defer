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

/**
 * This error reporter implementation MUST ignore all reported exceptions.
 * It SHALL NOT perform any logging or throw exceptions under any circumstances.
 * This class MAY be used as a default or placeholder reporter.
 */
final class NullErrorReporter implements ErrorReporterInterface
{
    /**
     * Ignores any reported throwable.
     *
     * This method MUST NOT perform any action and MUST NOT throw exceptions.
     *
     * @param Throwable $throwable the exception or error to ignore
     * @param callable|null $callback the related callback, if available
     * @param array $args arguments passed to the callback, if any
     *
     * @return void
     */
    public function report(Throwable $throwable, ?callable $callback = null, array $args = []): void {}
}
