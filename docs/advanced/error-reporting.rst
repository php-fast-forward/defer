Error Reporting
===============

When a deferred callback throws, ``Defer`` catches that throwable and forwards
it to the configured ``ErrorReporterInterface`` implementation. This keeps
cleanup code from failing silently and lets you choose how much visibility you
want in each environment.

How reporting is configured
---------------------------

Error reporting is configured globally for ``Defer`` through the static
``Defer::setErrorReporter()`` method.

.. code-block:: php

   use FastForward\Defer\Defer;
   use FastForward\Defer\ErrorReporter\NullErrorReporter;

   Defer::setErrorReporter(new NullErrorReporter());

Every new ``Defer`` instance created after that call uses the configured
reporter. Reset to the default ``error_log`` reporter with:

.. code-block:: php

   Defer::setErrorReporter(null);

Built-in reporters at a glance
------------------------------

.. list-table::
   :header-rows: 1

   * - Class
     - Purpose
     - Good fit
   * - ``ErrorLogErrorReporter``
     - Writes a readable message to ``error_log()``
     - Default behavior and simple production setups
   * - ``NullErrorReporter``
     - Ignores failures completely
     - Tests or flows where cleanup noise must stay silent
   * - ``CompositeErrorReporter``
     - Sends the same failure to multiple reporters
     - Logging plus metrics, logging plus events, and similar fan-out
   * - ``PsrLoggerErrorReporter``
     - Writes structured context to a PSR-3 logger
     - Applications already using Monolog or another PSR-3 implementation
   * - ``PsrEventDispatcherErrorReporter``
     - Dispatches a ``DeferredCallbackFailed`` event
     - Event-driven observability and decoupled listeners

Default behavior
----------------

If you never call ``Defer::setErrorReporter()``, the library lazily creates an
``ErrorLogErrorReporter`` instance and logs messages like:

- exception class and message
- source file and line
- a readable callback description

Custom reporters
----------------

You can implement ``ErrorReporterInterface`` to forward failures anywhere you
need.

.. code-block:: php

   use FastForward\Defer\Defer;
   use FastForward\Defer\ErrorReporterInterface;
   use Throwable;

   Defer::setErrorReporter(new class implements ErrorReporterInterface {
       public function report(Throwable $throwable, ?callable $callback = null, array $arguments = []): void
       {
           echo '[custom reporter] ' . $throwable::class . ': ' . $throwable->getMessage() . "\n";
       }
   });

Important caveat
----------------

Custom reporters should not throw. ``Defer`` catches the deferred callback
failure, but it does not wrap the reporter call in an extra safety layer. If
your reporter throws, the remaining cleanup chain may stop early.

If you need to defend against fragile reporters, wrap them with
``CompositeErrorReporter`` or use reporters that already guard their own
internal failures.

NullErrorReporter
-----------------

Use ``NullErrorReporter`` when you intentionally want silent cleanup failures.

.. code-block:: php

   use FastForward\Defer\ErrorReporter\NullErrorReporter;

   Defer::setErrorReporter(new NullErrorReporter());

CompositeErrorReporter
----------------------

``CompositeErrorReporter`` fans a failure out to multiple reporters. It also
catches failures thrown by one child reporter, logs that failure through
``error_log()``, and keeps reporting to the remaining child reporters.

.. code-block:: php

   use FastForward\Defer\ErrorReporter\CompositeErrorReporter;
   use FastForward\Defer\ErrorReporter\ErrorLogErrorReporter;
   use FastForward\Defer\ErrorReporter\PsrEventDispatcherErrorReporter;

   $reporter = new CompositeErrorReporter(
       new ErrorLogErrorReporter(),
       new PsrEventDispatcherErrorReporter($dispatcher),
   );

   if ($reporter->isEmpty()) {
       $reporter->add(new ErrorLogErrorReporter());
   }

   Defer::setErrorReporter($reporter);

The class also implements ``Countable``, which is useful in tests and diagnostic
code.

PSR-3 logger reporting
----------------------

``PsrLoggerErrorReporter`` sends a structured error entry to any
``Psr\Log\LoggerInterface``.

.. code-block:: php

   use FastForward\Defer\ErrorReporter\PsrLoggerErrorReporter;

   Defer::setErrorReporter(new PsrLoggerErrorReporter($logger));

The context includes:

- ``exception_class``
- ``message``
- ``file``
- ``line``
- ``callback``
- ``callback_arguments``
- ``exception``

Because the reporter delegates directly to your logger, prefer a logger that
does not throw during error handling.

PSR-14 event reporting
----------------------

``PsrEventDispatcherErrorReporter`` dispatches a
``FastForward\Defer\EventDispatcher\Event\DeferredCallbackFailed`` event.

.. code-block:: php

   use FastForward\Defer\ErrorReporter\PsrEventDispatcherErrorReporter;

   Defer::setErrorReporter(new PsrEventDispatcherErrorReporter($dispatcher));

This reporter is safer than a naïve custom reporter because it catches
dispatcher failures internally and writes them to ``error_log()``.

Related API
-----------

See :doc:`../api/error-reporters` for class-by-class reference details and
:doc:`../psr/index` for PSR-specific integration examples.
