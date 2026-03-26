Integrations
============

PSR-3 Logger
------------

You MAY integrate with any PSR-3 compatible logger for error reporting:

.. code-block:: php

   use FastForward\Defer\ErrorReporter\PsrLoggerErrorReporter;
   Defer::setErrorReporter(new PsrLoggerErrorReporter($logger));

PSR-14 Event Dispatcher
-----------------------

You MAY use a PSR-14 event dispatcher for event-driven error handling:

.. code-block:: php

   use FastForward\Defer\ErrorReporter\PsrEventDispatcherErrorReporter;
   Defer::setErrorReporter(new PsrEventDispatcherErrorReporter($dispatcher));

   // Listeners can react to DeferredCallbackFailed events

