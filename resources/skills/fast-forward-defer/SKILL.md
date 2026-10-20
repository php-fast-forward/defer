---
name: fast-forward-defer
description: Schedule LIFO cleanup callbacks in PHP scopes, preserve exception flow, and select explicit error reporting and PSR middleware integration.
license: MIT; see LICENSE
compatibility: Supports fast-forward/defer ^1.1 and PHP ^8.3. Requires Composer and declared extensions; inspect the installed version. Earlier releases are outside this contract.
metadata:
  author: Fast Forward
  api-baseline: fast-forward/defer ^1.1
---

# Manage scope cleanup with Defer

Use this skill for an application consuming `fast-forward/defer:^1.1`.
Read its instructions, manifest/lockfile and installed source before editing.
Run `composer show fast-forward/defer`. For versions outside this contract,
inspect matching documentation; do not silently upgrade the application.

## First working integration

Requires PHP ^8.3, Composer's application autoloader and this package's runtime
dependencies.
With authority to install the dependency:

```sh
composer require 'fast-forward/defer:^1.1'
```

Create `app.php` in the application root alongside composer.json/vendor:

```php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

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

echo "integration ready\n";
```

Run `php app.php`. It throws on a wrong result; success prints
`integration ready`. It uses no production data and starts no server.

## Wiring and failure boundaries

No container is required. A Defer instance owns a stack and runs it when the object is destroyed. scope() creates one for the callback and unsets its local reference in finally; PHP block braces alone are not object lifetime scopes. Do not capture the Defer in one of its own callbacks or retain it elsewhere when you need deterministic cleanup. Retained exception traces can also retain callback arguments when zend.exception_ignore_args is disabled; release caught exceptions before checking destructor effects. There is no public flush() API.

using($factory, $callback) passes the Defer to the factory and passes the produced resource to the callback. Register cleanup after acquiring a resource. Callbacks run in reverse registration order, receive the supplied arguments, and ordinary callback failures are forwarded to the selected ErrorReporterInterface. The default reporter uses error_log(). Keep reporters nonthrowing: an error thrown by a reporter is not caught by the callback catch, and can interrupt cleanup. Defer::setErrorReporter changes process-wide state; restore with setErrorReporter(null) when the application-owned temporary policy ends.

For PSR-3/PSR-14 reporters the application must explicitly require `psr/log:^3.0` or `psr/event-dispatcher:^1.0`. For middleware use DeferMiddleware, retrieve the request attribute DeferInterface::class (or the configured constructor attribute), and pass the request to the next PSR-15 handler. Missing/wrong attributes cause getDefer() to throw LogicException. A retained request also retains its Defer: do not promise cleanup on return if the request escapes. No service provider or hidden configuration key is supplied.

## Verify integration

Run the [complete example](examples/integration.php) against the application's
autoloader in a disposable process. It checks success and negative cases:

```sh
XDEBUG_MODE=off php /path/to/fast-forward-defer/examples/integration.php ./vendor/autoload.php
```

Disable Xdebug for this destructor timing check: debugging/tracing can retain
references beyond the application's scope. Expected final marker:
`fast-forward-defer: ok`. Keep application-specific regression
tests in its existing PHPUnit suite, with each fixture class in its same-named
file. File copying, host discovery and completed agent tasks are different checks.
For optional copying/synchronization load
[installation and lifecycle](references/installation.md).
