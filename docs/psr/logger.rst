PSR-3 Logger Integration
========================

Use ``PsrLoggerErrorReporter`` when your application already centralizes
operational visibility in a PSR-3 logger.

.. code-block:: php

   use FastForward\Defer\Defer;
   use FastForward\Defer\ErrorReporter\PsrLoggerErrorReporter;

   Defer::setErrorReporter(new PsrLoggerErrorReporter($logger));

The reporter writes one ``error()`` entry with structured context. The context
contains:

- ``exception_class``
- ``message``
- ``file``
- ``line``
- ``callback``
- ``callback_arguments``
- ``exception``

Example with Monolog
--------------------

.. code-block:: php

   use FastForward\Defer\Defer;

   $logger = new Monolog\Logger('defer');
   Defer::setErrorReporter(new PsrLoggerErrorReporter($logger));

Operational note
----------------

``PsrLoggerErrorReporter`` delegates directly to your logger. If the logger
throws, that exception can interrupt the remaining cleanup chain. In production,
prefer loggers and handlers that do not throw during error reporting.

Related pages
-------------

- :doc:`../advanced/error-reporting`
- :doc:`../api/error-reporters`
