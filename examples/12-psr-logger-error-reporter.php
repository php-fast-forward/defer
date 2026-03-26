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
use FastForward\Defer\ErrorReporter\PsrLoggerErrorReporter;
use Psr\Log\AbstractLogger;

require dirname(__DIR__) . '/vendor/autoload.php';

$logger = new class extends AbstractLogger {
    /**
     * @param mixed $level
     * @param mixed $message
     * @param array $context
     *
     * @return void
     */
    public function log($level, $message, array $context = []): void
    {
        echo '[' . $level . '] ' . $message . \PHP_EOL;
        echo json_encode($context, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES) . \PHP_EOL;
    }
};

Defer::setErrorReporter(new PsrLoggerErrorReporter($logger));

function run(): void
{
    $defer = new Defer();

    $defer(static function (): void {
        throw new RuntimeException('psr logger reporter example');
    });
}

run();
