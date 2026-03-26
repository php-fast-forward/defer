PSR-3 Logger Integration
========================

FastForward Defer can report errors using any PSR-3 compatible logger.

.. code-block:: php

   use FastForward\Defer\ErrorReporter\PsrLoggerErrorReporter;
   Defer::setErrorReporter(new PsrLoggerErrorReporter($logger));

You MAY use any logger that implements the PSR-3 interface, such as Monolog.

Example:

.. code-block:: php

   $logger = new Monolog\Logger('defer');
   Defer::setErrorReporter(new PsrLoggerErrorReporter($logger));
