<?php

declare(strict_types=1);

require $argv[1] ?? dirname(__DIR__, 4) . '/vendor/autoload.php';

use FastForward\Defer\Defer;
use FastForward\Defer\DeferInterface;
use function FastForward\Defer\scope;

$seen = [];
scope(static function (DeferInterface $defer) use (&$seen): void {
    $defer(static function () use (&$seen): void { $seen[] = 'first'; });
    $defer(static function () use (&$seen): void { $seen[] = 'second'; });
    if (count($defer) !== 2) {
        throw new RuntimeException('Deferred callback registration differs.');
    }
});
if ($seen !== ['second', 'first']) {
    throw new RuntimeException('Cleanup must run in LIFO order.');
}
$cleaned = false;
$propagated = false;
try {
    scope(static function (DeferInterface $defer) use (&$cleaned): void {
        $defer(static function () use (&$cleaned): void { $cleaned = true; });
        throw new RuntimeException('application failure');
    });
} catch (RuntimeException $error) {
    if ($error->getMessage() !== 'application failure') { throw $error; }
    $propagated = true;
}
unset($error);
if (!$propagated) {
    throw new RuntimeException('Application failure was not propagated.');
}
if (!$cleaned) {
    throw new RuntimeException('Cleanup did not run during exception unwinding.');
}

require __DIR__ . '/RecordingErrorReporter.php';
$reporter = new \Example\Defer\RecordingErrorReporter();
Defer::setErrorReporter($reporter);
try {
    scope(static function (DeferInterface $defer) use (&$seen): void {
        $defer(static function () use (&$seen): void { $seen[] = 'after-failure'; });
        $defer(static function (): never { throw new RuntimeException('callback failure'); });
    });
    if (count($reporter->errors) !== 1 || end($seen) !== 'after-failure') {
        throw new RuntimeException('Reporter did not observe failure or cleanup stopped.');
    }
} finally {
    Defer::setErrorReporter(null);
}

echo "fast-forward-defer: ok\n";
