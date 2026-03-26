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

require dirname(__DIR__) . '/vendor/autoload.php';

function inner(): void
{
    $defer = new Defer();

    $defer(static fn(): int => print "inner cleanup 1\n");
    $defer(static fn(): int => print "inner cleanup 2\n");

    echo "inside inner\n";
}

function outer(): void
{
    $defer = new Defer();

    $defer(static fn(): int => print "outer cleanup 1\n");
    $defer(static fn(): int => print "outer cleanup 2\n");

    echo "inside outer\n";

    inner();
}

outer();
