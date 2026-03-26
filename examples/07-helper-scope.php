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

use FastForward\Defer\DeferInterface;

use function FastForward\Defer\scope;

require dirname(__DIR__) . '/vendor/autoload.php';

scope(static function (DeferInterface $defer): void {
    $defer(static fn(): int => print "scope cleanup 1\n");
    $defer(static fn(): int => print "scope cleanup 2\n");

    echo "inside scope helper\n";
});
