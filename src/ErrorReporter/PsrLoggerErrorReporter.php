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
use Psr\Log\LoggerInterface;

/**
 * This error reporter implementation MUST log all reported exceptions using a PSR-3 compatible logger.
 * It SHALL provide a detailed log message including the exception class, message, file, line, callback description, and arguments.
 * This class MUST NOT throw exceptions during reporting.
 */
final readonly class PsrLoggerErrorReporter implements ErrorReporterInterface
{
    /**
     * Constructs a new PsrLoggerErrorReporter instance.
     *
     * @param LoggerInterface $logger the PSR-3 logger to use for error reporting
     */
    public function __construct(
        private LoggerInterface $logger
    ) {}

    /**
     * Reports a throwable using the PSR-3 logger.
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
        $this->logger->error(
            'Deferred callback failed: {exception_class}: {message}',
            [
                'exception_class' => $throwable::class,
                'message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'callback' => null !== $callback ? CallbackDescriber::describe($callback) : null,
                'callback_arguments' => $args,
                'exception' => $throwable,
            ]
        );
    }
}
