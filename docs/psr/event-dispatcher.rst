PSR-14 Event Dispatcher Integration
==================================

FastForward Defer supports event-driven error handling via PSR-14.

.. code-block:: php

   use FastForward\Defer\ErrorReporter\PsrEventDispatcherErrorReporter;
   Defer::setErrorReporter(new PsrEventDispatcherErrorReporter($dispatcher));

Listeners MAY react to DeferredCallbackFailed events for custom handling, logging, or metrics.
