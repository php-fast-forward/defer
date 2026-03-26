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
use FastForward\Defer\ErrorReporter\ErrorLogErrorReporter;

/**
 * This class MUST be used to manage deferred callbacks that SHALL be executed at the end of a scope.
 * It provides mechanisms to register, execute, and report errors for callbacks in a LIFO order.
 */
final class Defer implements DeferInterface
{
    /**
     * Stack of deferred callbacks and their arguments.
     *
     * @var array<int, array{0: callable, 1: array<int, mixed>}> the stack of callbacks
     */
    private array $stack = [];

    /**
     * The global error reporter instance. This property MAY be set to customize error reporting.
     */
    private static ?ErrorReporterInterface $errorReporter = null;

    /**
     * Constructs a new Defer instance and optionally registers an initial callback.
     *
     * @param callable|null $callback the initial callback to register (optional)
     * @param mixed ...$arguments Arguments for the callback.
     */
    public function __construct(?callable $callback = null, mixed ...$arguments)
    {
        if (null !== $callback) {
            $this->defer($callback, ...$arguments);
        }
    }

    /**
     * Executes all registered callbacks when the object is destroyed.
     *
     * @return mixed
     */
    public function __destruct()
    {
        $this->flush();
    }

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
    public function __invoke(callable $callback, mixed ...$arguments): void
    {
        $this->defer($callback, ...$arguments);
    }

    /**
     * Returns the number of registered callbacks.
     *
     * @return int the number of callbacks
     */
    public function count(): int
    {
        return \count($this->stack);
    }

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
    public function defer(callable $callback, mixed ...$args): void
    {
        $this->stack[] = [$callback, $args];
    }

    /**
     * Determines if there are no registered callbacks.
     *
     * @return bool true if no callbacks are registered; otherwise, false
     */
    public function isEmpty(): bool
    {
        return [] === $this->stack;
    }

    /**
     * Sets the global error reporter for all Defer instances.
     *
     * @param ErrorReporterInterface|null $reporter the error reporter to use
     *
     * @return void
     */
    public static function setErrorReporter(?ErrorReporterInterface $reporter): void
    {
        self::$errorReporter = $reporter;
    }

    /**
     * Gets the configured error reporter or instantiates a default one.
     *
     * @return ErrorReporterInterface the error reporter instance
     */
    private function getErrorReporter(): ErrorReporterInterface
    {
        return self::$errorReporter ??= new ErrorLogErrorReporter();
    }

    /**
     * Executes all registered callbacks, reporting exceptions as needed.
     *
     * This method MUST execute callbacks in LIFO order. If a callback throws, the error MUST be reported.
     *
     * @return void
     */
    private function flush(): void
    {
        while (($item = array_pop($this->stack)) !== null) {
            [$callback, $args] = $item;

            try {
                $callback(...$args);
            } catch (Throwable $throwable) {
                $this->getErrorReporter()
                    ->report($throwable, $callback, $args);
            }
        }
    }
}
