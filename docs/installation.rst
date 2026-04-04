Installation
============

``fast-forward/defer`` requires **PHP 8.3+** and Composer.

.. code-block:: bash

   composer require fast-forward/defer

Use ``fast-forward/defer`` as the Composer package name. That is the name
published on Packagist and declared in this repository's ``composer.json``.

No additional bootstrap step is required. Composer autoloads both the classes
and the helper functions defined in ``src/functions.php``.

Requirements
------------

- PHP 8.3 or newer
- Composer
- The runtime dependency tree declared by this package

Runtime dependencies
--------------------

The package currently declares these direct runtime dependencies:

- ``fast-forward/container`` for ecosystem consistency in the Fast Forward
  stack
- ``psr/http-server-middleware`` for the PSR-15 middleware type

When Composer resolves those packages it also pulls in their required PSR
interfaces and transitive packages. See :doc:`links/dependencies` for a summary.

Optional integrations
---------------------

These integrations are available through built-in classes, but your application
still needs concrete implementations:

- PSR-3 Logger (for advanced error reporting)
- PSR-14 Event Dispatcher (for event-driven error handling)
- PSR-15 HTTP Middleware (for request lifecycle management)

Quick verification
------------------

After installation, this smoke test should print ``inside`` and then
``cleanup``:

.. code-block:: php

   use FastForward\Defer\Defer;

   function run(): void
   {
       $defer = new Defer();
       $defer(fn(): int => print "cleanup\n");

       echo "inside\n";
   }

   run();

What this package does not require
----------------------------------

You do not need:

- a framework service provider
- a DI container
- a singleton bootstrap step
- a manual ``flush()`` call

The primary integration style is direct construction and short-lived use.
