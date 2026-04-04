Core API
========

``Defer``
---------

``FastForward\Defer\Defer`` is the concrete scope object used to collect and run
deferred callbacks.

Key behavior
~~~~~~~~~~~~

- The constructor may register one initial callback.
- ``__invoke()`` and ``defer()`` both add callbacks to the stack.
- ``count()`` and ``isEmpty()`` inspect the current stack.
- Cleanup runs during destruction; there is no public ``flush()`` method.
- ``setErrorReporter()`` changes the global reporter used by all instances.

Common usage
~~~~~~~~~~~~

.. code-block:: php

   use FastForward\Defer\Defer;

   $defer = new Defer();
   $defer(fn(): int => print "cleanup\n");

``DeferInterface``
------------------

``FastForward\Defer\DeferInterface`` is the narrow contract used by helper
functions and middleware.

Public members
~~~~~~~~~~~~~~

- ``__invoke(callable $callback, mixed ...$arguments): void``
- ``defer(callable $callback, mixed ...$arguments): void``
- ``isEmpty(): bool``
- ``Countable::count(): int``

Use the interface when your code only needs to register callbacks and should
not depend on the concrete class.

Helper functions
----------------

The package autoloads three helper functions:

``defer()``
~~~~~~~~~~~

Returns a new ``DeferInterface`` implementation.

``scope()``
~~~~~~~~~~~

Creates a temporary scope, passes it into your callback, and destroys it when
the callback finishes.

``using()``
~~~~~~~~~~~

Creates a temporary scope, asks a factory callback to build a resource, then
passes that resource into a second callback before cleanup runs.

What is intentionally missing
-----------------------------

The core API does not provide:

- a public manual flush method
- a singleton accessor for ``Defer``
- a framework alias
- a service provider

That keeps the cleanup boundary explicit in user code.
