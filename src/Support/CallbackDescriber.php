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

namespace FastForward\Defer\Support;

use Closure;
use ReflectionFunction;

/**
 * This utility class MUST be used to generate human-readable descriptions for any PHP callable.
 * It SHALL support closures, array callables, invokable objects, and string callables.
 * All methods MUST be static and MUST NOT throw exceptions.
 */
final class CallbackDescriber
{
    /**
     * Returns a human-readable description for a callable.
     *
     * This method MUST support closures, array callables, invokable objects, and string callables.
     *
     * @param callable $callback the callable to describe
     *
     * @return string the description of the callable
     */
    public static function describe(callable $callback): string
    {
        if (\is_string($callback)) {
            return $callback;
        }

        if ($callback instanceof Closure) {
            return self::describeClosure($callback);
        }

        if (\is_array($callback)) {
            return self::describeArrayCallable($callback);
        }

        if (\is_object($callback) && method_exists($callback, '__invoke')) {
            return $callback::class . '::__invoke';
        }

        return 'callable';
    }

    /**
     * Returns a description for a Closure, including file and line if available.
     *
     * @param Closure $closure the closure to describe
     *
     * @return string the description of the closure
     */
    private static function describeClosure(Closure $closure): string
    {
        $reflection = new ReflectionFunction($closure);
        $file = $reflection->getFileName();
        $line = $reflection->getStartLine();

        if (false === $file || false === $line) {
            return 'Closure';
        }

        return \sprintf('Closure@%s:%d', $file, $line);
    }

    /**
     * Returns a description for an array callable.
     *
     * @param array{0: object|string, 1: string} $callback the array callable to describe
     * @param array $callback
     *
     * @return string the description of the array callable
     */
    private static function describeArrayCallable(array $callback): string
    {
        [$target, $method] = $callback;

        if (\is_object($target)) {
            return $target::class . '->' . $method;
        }

        return $target . '::' . $method;
    }
}
