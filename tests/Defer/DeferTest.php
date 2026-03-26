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

use FastForward\Defer\ErrorReporterInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use FastForward\Defer\Defer;

#[CoversClass(Defer::class)]
final class DeferTest extends TestCase
{
    private Defer $defer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->defer = new Defer();
    }

    /**
     * @return void
     */
    #[Test]
    public function deferExecutesCallbacksInLifoOrder(): void
    {
        $result = [];
        $this->defer->defer(function () use (&$result): void { $result[] = 1; });
        $this->defer->defer(function () use (&$result): void { $result[] = 2; });
        unset($this->defer);
        gc_collect_cycles();
        self::assertSame([2, 1], $result);
    }

    /**
     * @return void
     */
    #[Test]
    public function deferInvokeAddsCallback(): void
    {
        $result = [];
        $this->defer->defer(function () use (&$result): void { $result[] = 'a'; });
        unset($this->defer);
        gc_collect_cycles();
        self::assertSame(['a'], $result);
    }

    /**
     * @return void
     */
    #[Test]
    public function countAndIsEmpty(): void
    {
        self::assertTrue($this->defer->isEmpty());
        self::assertSame(0, $this->defer->count());
        $this->defer->defer(fn(): null => null);
        self::assertFalse($this->defer->isEmpty());
        self::assertSame(1, $this->defer->count());
    }

    /**
     * @return void
     */
    #[Test]
    public function deferWithArguments(): void
    {
        $result = null;
        $this->defer->defer(function ($a, $b) use (&$result): void { $result = $a + $b; }, 2, 3);
        unset($this->defer);
        gc_collect_cycles();
        self::assertSame(5, $result);
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    #[Test]
    public function exceptionIsReported(): void
    {
        $reporter = new class implements ErrorReporterInterface {
            public bool $reported = false;

            /**
             * @param Throwable $throwable
             * @param callable|null $callback
             * @param array $arguments
             *
             * @return void
             */
            public function report(Throwable $throwable, ?callable $callback = null, array $arguments = []): void
            {
                $this->reported = true;
            }
        };
        Defer::setErrorReporter($reporter);
        $defer = new Defer(fn() => throw new Exception('fail'));
        unset($defer);
        gc_collect_cycles();
        self::assertTrue($reporter->reported);
        Defer::setErrorReporter(null); // reset
    }
}
