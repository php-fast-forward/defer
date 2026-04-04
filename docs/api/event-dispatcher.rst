Event Dispatcher API
====================

This package includes a small PSR-14 support layer around deferred callback
failures.

``DeferredCallbackFailed``
--------------------------

``FastForward\Defer\EventDispatcher\Event\DeferredCallbackFailed`` is a readonly
event object with three public properties:

- ``Throwable $throwable``
- ``?string $callback``
- ``array $arguments``

It is created by ``PsrEventDispatcherErrorReporter``.

``LogDeferredCallbackFailure``
------------------------------

``FastForward\Defer\EventDispatcher\Listener\LogDeferredCallbackFailure`` is an
invokable listener that takes a PSR-3 logger in the constructor and writes the
same structured failure context used by ``PsrLoggerErrorReporter``.

``DeferredCallbackListenerProvider``
------------------------------------

``FastForward\Defer\EventDispatcher\ListenerProvider\DeferredCallbackListenerProvider``
implements ``ListenerProviderInterface`` and ``LoggerAwareInterface``.

Important details
~~~~~~~~~~~~~~~~~

- It yields listeners only for ``DeferredCallbackFailed`` events.
- It defaults to ``Psr\Log\NullLogger`` when no logger is provided.
- ``setLogger()`` lets you replace the logger later.

How the pieces fit together
---------------------------

.. list-table::
   :header-rows: 1

   * - Step
     - Class
     - Role
   * - 1
     - ``PsrEventDispatcherErrorReporter``
     - Converts a cleanup failure into an event
   * - 2
     - ``DeferredCallbackFailed``
     - Carries the throwable, callback description, and arguments
   * - 3
     - ``DeferredCallbackListenerProvider``
     - Supplies listeners for that event type
   * - 4
     - ``LogDeferredCallbackFailure``
     - Logs the event with a PSR-3 logger
