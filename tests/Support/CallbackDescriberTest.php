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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use FastForward\Defer\Support\CallbackDescriber;

#[CoversClass(CallbackDescriber::class)]
final class CallbackDescriberTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function describeWithStringFunctionWillReturnName(): void
    {
        $desc = CallbackDescriber::describe('strlen');
        self::assertSame('strlen', $desc);
    }

    /**
     * @return void
     */
    #[Test]
    public function describeWithClosureWillReturnClosureString(): void
    {
        $closure = function (): void {};
        $desc = CallbackDescriber::describe($closure);
        self::assertStringContainsString('Closure@', $desc);
    }

    /**
     * @return void
     */
    #[Test]
    public function describeWithArrayCallableObjectWillReturnDescription(): void
    {
        $obj = new class {
            /**
             * @return void
             */
            public function foo(): void {}
        };
        $desc = CallbackDescriber::describe($obj->foo(...));
        self::assertTrue(
            str_contains($desc, '->foo') || str_contains($desc, 'Closure@'),
            'Description must contain ->foo or Closure@'
        );
    }

    /**
     * @return void
     */
    #[Test]
    public function describeArrayCallableStatic(): void
    {
        $desc = CallbackDescriber::describe([self::class, 'staticMethod']);
        self::assertStringContainsString('::staticMethod', $desc);
    }

    /**
     * @return mixed
     */
    public static function staticMethod(): void {}

    /**
     * @return void
     */
    #[Test]
    public function describeInvokableObject(): void
    {
        $obj = new class {
            /**
             * @return mixed
             */
            public function __invoke(): void {}
        };
        $desc = CallbackDescriber::describe($obj);
        self::assertStringContainsString('__invoke', $desc);
    }
}
