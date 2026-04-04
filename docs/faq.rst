FAQ
===

When do deferred callbacks actually run?
----------------------------------------

They run when the ``Defer`` instance is destroyed. In a normal function that is
usually when the function returns or throws. If you need an explicit boundary,
use :doc:`usage` with ``scope()`` or ``unset($defer)``.

Why do callbacks run in reverse order?
--------------------------------------

Because cleanup is usually safest in reverse acquisition order. Close the file
before deleting it, release the lock after flushing data, and so on. See
:doc:`usage`.

What happens if one deferred callback throws?
---------------------------------------------

``Defer`` catches that throwable and forwards it to the configured error
reporter. See :doc:`advanced/error-reporting`.

Can a failing reporter stop the rest of the cleanup chain?
----------------------------------------------------------

Yes, a custom reporter that throws can interrupt the remaining chain because the
reporter call itself is not wrapped by ``Defer``. Prefer non-throwing reporters
or ``CompositeErrorReporter``. See :doc:`advanced/error-reporting`.

Should I keep one ``Defer`` instance in a singleton or shared service?
----------------------------------------------------------------------

No. ``Defer`` is meant to be short-lived and scope-bound. Create a new instance
per request, command, job, or temporary block. See :doc:`advanced/usage` and
:doc:`compatibility`.

Can I inspect whether cleanup callbacks are pending?
----------------------------------------------------

Yes. ``Defer`` implements ``Countable`` and exposes ``isEmpty()``. See
:doc:`usage` and :doc:`api/core`.

Does this package provide a PSR-11 service provider?
----------------------------------------------------

No. Register it in your container with a simple factory if you want container
integration. See :doc:`advanced/integrations`.

How do I use it in a PSR-15 application?
----------------------------------------

Wrap your pipeline with ``DeferMiddleware`` and retrieve the scope from the
request attributes. See :doc:`psr/middleware`.

Can I listen to cleanup failures as PSR-14 events?
--------------------------------------------------

Yes. Use ``PsrEventDispatcherErrorReporter``. The package also includes
``DeferredCallbackFailed``, ``DeferredCallbackListenerProvider``, and
``LogDeferredCallbackFailure``. See :doc:`psr/event-dispatcher`.

What is ``CallbackDescriber`` for?
----------------------------------

It turns PHP callables into readable strings so logs and events can tell you
which callback failed. See :doc:`advanced/callback-describer`.

Is ``using()`` required, or can I stay with plain ``Defer``?
------------------------------------------------------------

Plain ``Defer`` is enough for most code. ``using()`` is just a convenient shape
for acquire -> use -> cleanup flows. See :doc:`usage`.

Where should I start if I want a practical walkthrough?
-------------------------------------------------------

Start with :doc:`getting-started`, then run the numbered scripts in
:doc:`examples/index`.
