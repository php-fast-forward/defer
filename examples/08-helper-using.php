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

use function FastForward\Defer\using;

require dirname(__DIR__) . '/vendor/autoload.php';

using(
    static function (DeferInterface $defer) {
        $filename = __DIR__ . '/using-example.txt';
        $file = fopen($filename, 'w+');

        if (false === $file) {
            throw new RuntimeException('Unable to open ' . $filename);
        }

        $defer(static fn(): bool => unlink($filename));
        $defer(static fn(): int => print file_get_contents($filename) . \PHP_EOL);
        $defer(static fn(): bool => fclose($file));

        return $file;
    },
    static function ($file): void {
        fwrite($file, "hello from using()\n");
    }
);
