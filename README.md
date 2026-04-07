# FastForward Defer

[![PHP Version](https://img.shields.io/badge/php-^8.3-777BB4?logo=php&logoColor=white)](https://www.php.net/releases/)
[![Composer Package](https://img.shields.io/badge/composer-fast--forward%2Fdefer-F28D1A.svg?logo=composer&logoColor=white)](https://packagist.org/packages/fast-forward/defer)
[![Tests](https://img.shields.io/github/actions/workflow/status/php-fast-forward/defer/tests.yml?logo=githubactions&logoColor=white&label=tests&color=22C55E)](https://github.com/php-fast-forward/defer/actions/workflows/tests.yml)
[![Coverage](https://img.shields.io/badge/coverage-phpunit-4ADE80?logo=php&logoColor=white)](https://php-fast-forward.github.io/defer/coverage/index.html)
[![Docs](https://img.shields.io/github/deployments/php-fast-forward/defer/github-pages?logo=readthedocs&logoColor=white&label=docs&labelColor=1E293B&color=38BDF8&style=flat)](https://php-fast-forward.github.io/defer/index.html)
[![License](https://img.shields.io/github/license/php-fast-forward/defer?color=64748B)](LICENSE)
[![GitHub Sponsors](https://img.shields.io/github/sponsors/php-fast-forward?logo=githubsponsors&logoColor=white&color=EC4899)](https://github.com/sponsors/php-fast-forward)

A minimal utility that brings **defer-style execution** (similar to Go) to PHP using object scope and destructors.
It allows you to register callbacks that will run **automatically at the end of a scope**, in **LIFO order (Last-In, First-Out)**.

---

## Core Concept

A Defer instance represents a **scope-bound execution stack**.

- Register callbacks using $defer(...) or $defer->defer(...)
- Callbacks execute automatically when the object goes out of scope
- Execution is always **LIFO (stack behavior)**
- Execution is **guaranteed during exception unwinding**
- Errors inside callbacks are **captured and reported**, without interrupting the chain

---

## Basic Usage

```php
use FastForward\Defer\Defer;

function example(): void
{
    $defer = new Defer();

    $defer(function () {
        echo "First defer\n";
    });

    $defer(function () {
        echo "Second defer\n";
    });

    echo "Inside function\n";
}

example();
```

### Output

```plain
Inside function
Second defer
First defer
```

---

## Using Arguments

Deferred callbacks receive exactly the arguments you pass.

```php
function example(): void
{
    $defer = new Defer();

    $defer(function ($file) {
        echo "Deleting {$file}\n";
    }, 'temp.txt');

    echo "Working...\n";
}

example();
```

### Output

```plain
Working...
Deleting temp.txt
```

---

## ⚠️ Execution Order Matters

Deferred callbacks execute in **reverse order of registration**.

```php
$defer(fn() => unlink($file));
$defer(fn() => fclose($handle));
```

Execution order:

```php
fclose($handle)
unlink($file)
```

Always register in **reverse of the desired execution order**.

---

## Exception Safety

Deferred callbacks always run, even if an exception occurs.

```php
function process(): void
{
    $defer = new Defer();

    $defer(fn() => print "Cleanup\n");

    throw new Exception('Failure');
}

try {
    process();
} catch (Exception) {
    echo "Exception caught\n";
}
```

### Output

```
Cleanup
Exception caught
```

---

## Nested Scopes

Each Defer instance is isolated.

```php
function outer(): void
{
    $defer = new Defer();

    $defer(fn() => print "Outer cleanup\n");

    inner();
}

function inner(): void
{
    $defer = new Defer();

    $defer(fn() => print "Inner cleanup\n");
}

outer();
```

### Output

```plain
Inner cleanup
Outer cleanup
```

---

## Helper Functions

### defer()

Creates a new Defer instance.

```plain
Inside function
Second defer
First defer0
```

---

### scope()

Runs a block with an isolated defer scope.

```php
use function FastForward\Defer\scope;

scope(function ($defer) {
    $defer(fn() => print "Cleanup\n");

    echo "Inside\n";
});
```

---

### using()

Structured resource management (acquire → use → cleanup).

```plain
Inside function
Second defer
First defer2
```

---

## Error Handling

Deferred callbacks **never break execution flow**.

- If a callback throws, execution continues
- Errors are forwarded to an ErrorReporter
- Default behavior uses error_log()

---

### Custom Error Reporter

```plain
Inside function
Second defer
First defer3
```

---

### Composite Reporter

```plain
Inside function
Second defer
First defer4
```

---

## PSR Integration

### PSR-3 Logger

```php
use FastForward\Defer\ErrorReporter\PsrLoggerErrorReporter;

Defer::setErrorReporter(
    new PsrLoggerErrorReporter($logger)
);
```

---

### PSR-14 Event Dispatcher

```plain
Inside function
Second defer
First defer6
```

This allows multiple listeners (logging, metrics, tracing, etc.).

---

## HTTP Middleware (PSR-15)

You can bind a Defer instance to a request lifecycle.

```plain
Inside function
Second defer
First defer7
```

The middleware:

- creates a Defer per request
- injects it into request attributes
- ensures execution at the end of the request

---

### Accessing Defer in Handlers

```php
use FastForward\Defer\DeferInterface;

$defer = $request->getAttribute(DeferInterface::class);
$defer(fn() => cleanup());
```

---

## Execution Model

### Within a single scope

- Last registered → runs first

### Across nested scopes

- Inner scope resolves before outer

---

## Design Principles

- **Deterministic cleanup**
- **Minimal API**
- **No manual flush**
- **Failure isolation**
- **Extensible reporting**

---

## Notes

- Execution is triggered by __destruct()
- Do not share instances across scopes
- Prefer short-lived instances
- Avoid long-lived/global usage

---

## When to Use

- resource cleanup
- file handling
- locks
- temporary state
- exception-safe teardown

---

## When NOT to Use

- long-lived lifecycle management
- orchestration logic
- cases requiring explicit execution timing

---

## Summary

Defer provides a strict and predictable cleanup model:

- automatic execution at scope end
- LIFO ordering
- safe failure handling
- pluggable reporting
- PSR-friendly integrations

It is intentionally **small, deterministic, and constrained**.
