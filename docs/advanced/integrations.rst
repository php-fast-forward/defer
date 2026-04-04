Integrations
============

This package is intentionally lightweight. There is no framework-specific
service provider, alias map, or singleton accessor for ``Defer`` itself. The
primary entry point is direct construction.

Direct instantiation
--------------------

.. code-block:: php

   use FastForward\Defer\Defer;

   $defer = new Defer();

PSR-11 container registration
-----------------------------

The package does not depend on PSR-11 directly, but it fits cleanly into any
container that can execute a factory.

.. code-block:: php

   use FastForward\Defer\Defer;
   use FastForward\Defer\DeferInterface;
   use FastForward\Defer\Middleware\DeferMiddleware;

   $container->set(DeferInterface::class, static fn (): DeferInterface => new Defer());
   $container->set(DeferMiddleware::class, static fn (): DeferMiddleware => new DeferMiddleware());

Register one scope per unit of work
-----------------------------------

``Defer`` works best when each request, command, job, or operation gets its own
fresh scope.

Good examples:

- one ``Defer`` per CLI command execution
- one ``Defer`` per queue job
- one ``Defer`` per PSR-15 request
- one inner ``scope()`` block for a temporary subtask

Poor fits:

- one global ``Defer`` shared across unrelated work
- storing ``Defer`` inside a singleton service
- static properties that are released only at process shutdown

CLI and worker loops
--------------------

For long-running processes, create a new scope inside each iteration instead of
reusing one forever.

.. code-block:: php

   use FastForward\Defer\Defer;

   while ($job = $queue->next()) {
       $defer = new Defer();

       $defer(fn() => $job->releaseTemporaryResources());

       $processor->handle($job);

       unset($defer);
   }

PSR integrations
----------------

The package includes ready-to-use integrations for:

- PSR-3 via ``PsrLoggerErrorReporter``
- PSR-14 via ``PsrEventDispatcherErrorReporter``
- PSR-15 via ``DeferMiddleware``

See :doc:`../psr/index` for focused examples.

What is not provided
--------------------

The package currently does not provide:

- a container service provider
- alias registration
- a singleton instance registry
- framework-specific bootstrapping

That is deliberate. Cleanup scope should remain explicit in the host
application.
