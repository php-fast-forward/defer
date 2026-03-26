Error Reporting
==============

FastForward Defer provides robust error handling for deferred callbacks. Errors thrown by callbacks do not interrupt the execution chain; instead, they are reported via a pluggable error reporter.

Default Behavior
----------------

By default, errors are reported using PHP's `error_log()`.

Custom Error Reporter
---------------------

You MAY provide a custom error reporter by implementing `ErrorReporterInterface`:

.. code-block:: php

   use FastForward\Defer\Defer;
   use FastForward\Defer\ErrorReporterInterface;

   Defer::setErrorReporter(new class implements ErrorReporterInterface {
       public function report(Throwable $throwable, ?callable $callback = null, array $args = []): void {
           echo '[custom reporter] ' . $throwable::class . ': ' . $throwable->getMessage() . "\n";
       }
   });

NullErrorReporter
-----------------

Suppresses all error output:

.. code-block:: php

   use FastForward\Defer\ErrorReporter\NullErrorReporter;
   Defer::setErrorReporter(new NullErrorReporter());

CompositeErrorReporter
----------------------

Allows reporting to multiple destinations:

.. code-block:: php

   use FastForward\Defer\ErrorReporter\CompositeErrorReporter;
   use FastForward\Defer\ErrorReporter\ErrorLogErrorReporter;

   $stdoutReporter = new class implements ErrorReporterInterface {
       public function report(Throwable $throwable, ?callable $callback = null, array $args = []): void {
           echo '[stdout] ' . $throwable->getMessage() . "\n";
       }
   };

   Defer::setErrorReporter(new CompositeErrorReporter(new ErrorLogErrorReporter(), $stdoutReporter));
