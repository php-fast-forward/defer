HTTP Middleware
===============

FastForward Defer provides PSR-15 middleware for request-scoped deferred execution.

.. code-block:: php

   use FastForward\Defer\Middleware\DeferMiddleware;
   $middleware = new DeferMiddleware();

   // In your PSR-15 pipeline:
   $response = $middleware->process($request, $handler);

The middleware:

- Creates a Defer instance per request
- Injects it into request attributes
- Ensures execution at the end of the request

Accessing Defer in Request Handlers
-----------------------------------

.. code-block:: php

   use FastForward\Defer\DeferInterface;
   $defer = $request->getAttribute(DeferInterface::class);
   $defer(fn() => cleanup());
