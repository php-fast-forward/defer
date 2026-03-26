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

namespace FastForward\Defer\Tests;

use FastForward\Defer\Defer;
use FastForward\Defer\DeferInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\UsesClass;

use function FastForward\Defer\defer;
use function FastForward\Defer\using;
use function FastForward\Defer\scope;

#[UsesClass(Defer::class)]
#[CoversFunction(Defer::class)]
#[CoversFunction('FastForward\Defer\using')]
#[CoversFunction('FastForward\Defer\scope')]
final class FunctionsTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function deferFunctionWithoutInputWillReturnDeferInstance(): void
    {
        $d = defer();
        self::assertInstanceOf(DeferInterface::class, $d);
    }

    /**
     * @return void
     */
    #[Test]
    public function usingWithCallbackWillFlushAndReturnResult(): void
    {
        $flushed = false;
        $result = using(
            fn($defer) => $defer,
            function ($defer) use (&$flushed): int {
                $defer->defer(function () use (&$flushed): void { $flushed = true; });

                return 42;
            }
        );
        gc_collect_cycles();
        self::assertTrue($flushed);
        self::assertSame(42, $result);
    }

    /**
     * @return void
     */
    #[Test]
    public function scopeWithCallbackWillFlushAndReturnResult(): void
    {
        $flushed = false;
        $result = scope(function ($defer) use (&$flushed): int {
            $defer->defer(function () use (&$flushed): void { $flushed = true; });

            return 24;
        });
        gc_collect_cycles();
        self::assertTrue($flushed);
        self::assertSame(24, $result);
    }
}
