Error Reporter API
==================

``ErrorReporterInterface``
--------------------------

``FastForward\Defer\ErrorReporterInterface`` defines one method:

.. code-block:: php

   public function report(Throwable $throwable, ?callable $callback = null, array $arguments = []): void;

The deferred callback itself has already failed by the time this method is
called. The reporter decides what to do with that failure.

Built-in implementations
------------------------

.. list-table::
   :header-rows: 1

   * - Class
     - Responsibilities
   * - ``ErrorLogErrorReporter``
     - Writes a formatted message to ``error_log()`` and includes a callback
       description
   * - ``NullErrorReporter``
     - Ignores the failure completely
   * - ``CompositeErrorReporter``
     - Forwards the failure to every child reporter, supports ``add()``,
       ``count()``, and ``isEmpty()``
   * - ``PsrLoggerErrorReporter``
     - Logs the failure and structured context to a PSR-3 logger
   * - ``PsrEventDispatcherErrorReporter``
     - Dispatches a ``DeferredCallbackFailed`` event through a PSR-14
       dispatcher

``ErrorLogErrorReporter``
-------------------------

Best default choice when you want immediate visibility with no extra plumbing.

``NullErrorReporter``
---------------------

Useful in tests or intentionally silent execution flows.

``CompositeErrorReporter``
--------------------------

This class is also ``Countable`` and exposes:

- ``add(ErrorReporterInterface $reporter): self``
- ``isEmpty(): bool``
- ``count(): int``

It catches failures thrown by child reporters and writes those failures to
``error_log()`` before continuing to the remaining reporters.

``PsrLoggerErrorReporter``
--------------------------

This reporter logs with the message:

``Deferred callback failed: {exception_class}: {message}``

and the structured context described in :doc:`../psr/logger`.

``PsrEventDispatcherErrorReporter``
-----------------------------------

This reporter dispatches a ``DeferredCallbackFailed`` event and catches
dispatcher failures internally, logging them through ``error_log()``.
