PSR-15 Middleware Integration
============================

FastForward Defer provides PSR-15 middleware for request-scoped deferred execution.

.. code-block:: php

   use FastForward\Defer\Middleware\DeferMiddleware;
   // Default attribute is DeferInterface::class
   $middleware = new DeferMiddleware();
   // Or customize the attribute name:
   $middleware = new DeferMiddleware('custom.defer');
   $response = $middleware->process($request, $handler);

The middleware:

- Creates a Defer instance per request
- Injects it into request attributes (default: DeferInterface::class, or custom via $attribute)
- Ensures execution at the end of the request

You MAY access the Defer instance in handlers via:

.. code-block:: php

   use FastForward\Defer\DeferInterface;
   // Default:
   $defer = $request->getAttribute(DeferInterface::class);
   // If using a custom attribute:
   $defer = $request->getAttribute('custom.defer');
   $defer(fn() => cleanup());
