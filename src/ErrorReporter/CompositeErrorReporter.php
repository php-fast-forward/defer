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

use Countable;
use Throwable;
use FastForward\Defer\ErrorReporterInterface;

/**
 * Aggregates multiple error reporters and delegates error reporting to all of them.
 * This class MUST be used when you want to ensure that all registered reporters are notified of an error.
 * If any reporter throws an exception, this class SHALL log the failure using error_log and MUST continue reporting to the remaining reporters.
 */
final class CompositeErrorReporter implements ErrorReporterInterface, Countable
{
    /**
     * List of error reporters. This property MUST contain only valid ErrorReporterInterface implementations.
     *
     * @var ErrorReporterInterface[]
     */
    private array $reporters;

    /**
     * Constructs a new CompositeErrorReporter instance.
     *
     * This constructor SHALL accept any number of ErrorReporterInterface implementations.
     * The reporters MUST be stored in the order provided.
     *
     * @param ErrorReporterInterface ...$reporters One or more error reporters to aggregate.
     */
    public function __construct(ErrorReporterInterface ...$reporters)
    {
        $this->reporters = $reporters;
    }

    /**
     * Reports a throwable to all registered reporters.
     *
     * This method MUST attempt to report the throwable to each reporter in sequence.
     * If a reporter throws an exception, the failure MUST be logged using error_log,
     * and reporting SHALL continue for the remaining reporters.
     *
     * @param Throwable $throwable the exception or error to report
     * @param callable|null $callback the related callback, if available
     * @param array $args arguments passed to the callback, if any
     *
     * @return void
     */
    public function report(Throwable $throwable, ?callable $callback = null, array $args = []): void
    {
        foreach ($this->reporters as $reporter) {
            try {
                $reporter->report($throwable, $callback, $args);
            } catch (Throwable $reportingFailure) {
                error_log(
                    \sprintf(
                        '[%s] Composite reporter failed: %s: %s in %s:%d',
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

    /**
     * Adds a new reporter to the composite.
     *
     * This method MUST append the reporter to the internal list. The reporter MUST implement ErrorReporterInterface.
     *
     * @param ErrorReporterInterface $reporter the reporter to add
     *
     * @return self returns the current instance for chaining
     */
    public function add(ErrorReporterInterface $reporter): self
    {
        $this->reporters[] = $reporter;

        return $this;
    }

    /**
     * Determines if the composite contains no reporters.
     *
     * This method MUST return true if no reporters are registered, and false otherwise.
     *
     * @return bool true if no reporters are registered; otherwise, false
     */
    public function isEmpty(): bool
    {
        return [] === $this->reporters;
    }

    /**
     * Returns the number of registered reporters.
     *
     * This method MUST return the count of reporters currently registered in the composite.
     *
     * @return int the number of reporters
     */
    public function count(): int
    {
        return \count($this->reporters);
    }
}
