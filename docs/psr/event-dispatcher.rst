PSR-14 Event Dispatcher Integration
===================================

Use ``PsrEventDispatcherErrorReporter`` when cleanup failures should become
events instead of being logged directly.

.. code-block:: php

   use FastForward\Defer\Defer;
   use FastForward\Defer\ErrorReporter\PsrEventDispatcherErrorReporter;

   Defer::setErrorReporter(new PsrEventDispatcherErrorReporter($dispatcher));

The reporter dispatches a
``FastForward\Defer\EventDispatcher\Event\DeferredCallbackFailed`` event with
three public readonly properties:

- ``throwable``
- ``callback``
- ``arguments``

Using the packaged listener provider
------------------------------------

The package also ships:

- ``DeferredCallbackListenerProvider``
- ``LogDeferredCallbackFailure``

That pair is useful when your PSR-14 dispatcher expects a listener provider and
you want logging behavior out of the box.

.. code-block:: php

   use FastForward\Defer\EventDispatcher\ListenerProvider\DeferredCallbackListenerProvider;

   $provider = new DeferredCallbackListenerProvider($logger);

   // Build your PSR-14 dispatcher with this provider, depending on the
   // dispatcher implementation you use in your application.

Why use events here
-------------------

Events are a good fit when one failure should feed multiple concerns without
hard-coding them into one reporter:

- logging
- metrics
- tracing
- alerts
- custom recovery actions

Safety note
-----------

``PsrEventDispatcherErrorReporter`` catches dispatcher failures internally and
logs them with ``error_log()``. That makes it a safer choice than many naïve
custom reporters.

Related pages
-------------

- :doc:`../advanced/error-reporting`
- :doc:`../api/event-dispatcher`
