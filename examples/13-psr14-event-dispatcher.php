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
use FastForward\Defer\ErrorReporter\PsrEventDispatcherErrorReporter;
use FastForward\Defer\EventDispatcher\Event\DeferredCallbackFailed;
use Psr\EventDispatcher\EventDispatcherInterface;

require dirname(__DIR__) . '/vendor/autoload.php';

$dispatcher = new class implements EventDispatcherInterface {
    /**
     * @param object $event
     *
     * @return object
     */
    public function dispatch(object $event): object
    {
        if ($event instanceof DeferredCallbackFailed) {
            echo '[event] ' . $event->throwable->getMessage() . \PHP_EOL;
            echo '[callback] ' . ($event->callback ?? 'unknown') . \PHP_EOL;
        }

        return $event;
    }
};

Defer::setErrorReporter(new PsrEventDispatcherErrorReporter($dispatcher));

function run(): void
{
    $defer = new Defer();

    $defer(static function (): void {
        throw new RuntimeException('psr-14 event example');
    });
}

run();
