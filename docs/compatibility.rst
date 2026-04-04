Compatibility
=============

Runtime expectations
--------------------

.. list-table::
   :header-rows: 1

   * - Topic
     - Status
     - Notes
   * - PHP version
     - Supported
     - PHP 8.3 or newer
   * - Composer installation
     - Supported
     - Install with ``fast-forward/defer``
   * - PSR-15 middleware
     - Supported
     - Through ``DeferMiddleware``
   * - PSR-3 logging
     - Supported
     - Through ``PsrLoggerErrorReporter``
   * - PSR-14 event dispatching
     - Supported
     - Through ``PsrEventDispatcherErrorReporter``

Destructor-based execution model
--------------------------------

This package depends on normal PHP object destruction semantics. That makes it a
great fit for:

- synchronous CLI commands
- request/response applications
- background workers that create a fresh scope per job
- temporary inner scopes inside larger methods

Be explicit in long-lived processes
-----------------------------------

If your process stays alive for a long time, do not rely on script shutdown as
your cleanup boundary. Create and destroy a scope per unit of work.

Good pattern:

.. code-block:: php

   while ($job = $queue->next()) {
       $defer = new FastForward\Defer\Defer();

       // work

       unset($defer);
   }

Less suitable pattern:

- one global ``Defer`` for the whole worker process
- a static ``Defer`` property reused across unrelated operations

Global error reporter scope
---------------------------

``Defer::setErrorReporter()`` changes a static reporter shared by all ``Defer``
instances in the current PHP process. That is convenient, but it also means:

- tests should reset the reporter after changing it
- long-lived workers should configure it intentionally at bootstrap time
- request-local reporter changes should be avoided unless you fully control the
  whole process
