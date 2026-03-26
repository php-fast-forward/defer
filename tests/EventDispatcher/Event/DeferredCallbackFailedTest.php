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

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use FastForward\Defer\EventDispatcher\Event\DeferredCallbackFailed;

#[CoversClass(DeferredCallbackFailed::class)]
final class DeferredCallbackFailedTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function constructorWithArgumentsWillSetProperties(): void
    {
        $throwable = new Exception('fail');
        $event = new DeferredCallbackFailed($throwable, 'cb', [
            'a' => 1,
        ]);
        self::assertSame($throwable, $event->throwable);
        self::assertSame('cb', $event->callback);
        self::assertSame([
            'a' => 1,
        ], $event->arguments);
    }
}
