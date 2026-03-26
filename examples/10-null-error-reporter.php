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
use FastForward\Defer\ErrorReporter\NullErrorReporter;

require dirname(__DIR__) . '/vendor/autoload.php';

Defer::setErrorReporter(new NullErrorReporter());

function run(): void
{
    $defer = new Defer();

    $defer(static function (): void {
        echo "chain continues silently\n";
    });

    $defer(static function (): void {
        throw new RuntimeException('this will be ignored');
    });
}

run();
