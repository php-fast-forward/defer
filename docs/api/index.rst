API Reference
=============

This section maps the public classes, interfaces, and helper functions exposed
by ``fast-forward/defer``.

Overview
--------

.. list-table::
   :header-rows: 1

   * - Area
     - Main types
     - Purpose
   * - Core scope API
     - ``Defer``, ``DeferInterface``, ``defer()``, ``scope()``, ``using()``
     - Register and execute deferred callbacks
   * - Error reporting
     - ``ErrorReporterInterface`` and built-in reporters
     - Observe failures without embedding logging logic in callbacks
   * - Event dispatcher support
     - ``DeferredCallbackFailed``, ``DeferredCallbackListenerProvider``,
       ``LogDeferredCallbackFailure``
     - Turn failures into structured events and listeners
   * - HTTP middleware
     - ``DeferMiddleware``
     - Attach one defer scope to one PSR-15 request
   * - Support utilities
     - ``CallbackDescriber``
     - Describe callables for logs and events

.. toctree::
   :maxdepth: 2
   :caption: API Pages:

   core
   error-reporters
   event-dispatcher
   middleware
   support
