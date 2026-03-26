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

use FastForward\Defer\Defer;
use FastForward\Defer\ErrorReporterInterface;

require dirname(__DIR__) . '/vendor/autoload.php';

Defer::setErrorReporter(new class implements ErrorReporterInterface {
    /**
     * @param Throwable $throwable
     * @param callable|null $callback
     * @param array $args
     *
     * @return void
     */
    public function report(Throwable $throwable, ?callable $callback = null, array $args = []): void
    {
        echo '[custom reporter] ' . $throwable::class . ': ' . $throwable->getMessage() . \PHP_EOL;
    }
});

function run(): void
{
    $defer = new Defer();

    $defer(static function (): void {
        throw new RuntimeException('deferred failure');
    });

    $defer(static function (): void {
        echo "still running deferred chain\n";
    });
}

run();
