HTTP Middleware
===============

``DeferMiddleware`` binds deferred cleanup to the lifetime of a single PSR-15
request.

.. code-block:: php

   use FastForward\Defer\Middleware\DeferMiddleware;
   use FastForward\Defer\DeferInterface;

   $middleware = new DeferMiddleware();

   // In your PSR-15 pipeline:
   $response = $middleware->process($request, $handler);

What the middleware does
------------------------

- Creates a new ``Defer`` instance for the current request
- Injects it into the request attributes
- Unsets it in a ``finally`` block so cleanup runs even if the handler throws

Default request attribute
-------------------------

By default the attribute name is ``DeferInterface::class``.

.. code-block:: php

   use FastForward\Defer\DeferInterface;

   $defer = $request->getAttribute(DeferInterface::class);
   $defer(fn(): int => print "request cleanup\n");

Custom attribute names
----------------------

Use a custom attribute when your application prefers named request attributes.

.. code-block:: php

   use FastForward\Defer\Middleware\DeferMiddleware;

   $middleware = new DeferMiddleware('request.defer');

   // later
   $defer = $request->getAttribute('request.defer');

Using ``getDefer()`` for validated access
-----------------------------------------

``DeferMiddleware::getDefer()`` reads the configured attribute and validates the
type for you.

.. code-block:: php

   $defer = $middleware->getDefer($request);

If the attribute is missing or is not a ``DeferInterface``, the method throws a
``LogicException``. That is useful when you want one clear failure mode instead
of repeating manual attribute checks.

Lifecycle notes
---------------

Keep the request-scoped ``Defer`` inside the request lifecycle. Do not store it
for later asynchronous work or global reuse.

Related pages
-------------

- :doc:`../psr/middleware`
- :doc:`../api/middleware`
- :doc:`../compatibility`
