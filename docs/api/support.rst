Support Utilities
=================

``CallbackDescriber``
---------------------

``FastForward\Defer\Support\CallbackDescriber`` exposes one public static
method:

.. code-block:: php

   public static function describe(callable $callback): string

Returned string shapes
----------------------

.. list-table::
   :header-rows: 1

   * - Callable form
     - Typical output
   * - ``'strlen'``
     - ``strlen``
   * - ``[SomeClass::class, 'handle']``
     - ``SomeClass::handle``
   * - ``[$object, 'handle']``
     - ``SomeClass->handle``
   * - Closure
     - ``Closure@/path/to/file.php:123``
   * - Invokable object
     - ``SomeClass::__invoke``

Important nuance
----------------

First-class callables such as ``SomeClass::handle(...)`` are closures in PHP.
That means they are described as closures, not as ``SomeClass::handle``.

Where it is used
----------------

- ``ErrorLogErrorReporter``
- ``PsrLoggerErrorReporter``
- ``PsrEventDispatcherErrorReporter``
