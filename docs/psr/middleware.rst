PSR-15 Middleware Integration
=============================

``DeferMiddleware`` creates one defer scope per PSR-15 request and stores it in
the request attributes.

.. code-block:: php

   use FastForward\Defer\Middleware\DeferMiddleware;
   use FastForward\Defer\DeferInterface;

   // Default attribute is DeferInterface::class
   $middleware = new DeferMiddleware();

   // Or customize the attribute name:
   $middleware = new DeferMiddleware('custom.defer');
   $response = $middleware->process($request, $handler);

Handler usage
-------------

Inside the handler you can retrieve the scope from the request and register
cleanup work that should happen once the request is finishing:

.. code-block:: php

   use FastForward\Defer\DeferInterface;

   $defer = $request->getAttribute(DeferInterface::class);
   $defer(fn() => cleanup());

What the middleware guarantees
------------------------------

- Creates a Defer instance per request
- Injects it into request attributes
- Unsets it in a ``finally`` block after the handler returns or throws

Default and custom attributes
-----------------------------

.. code-block:: php

   $defer = $request->getAttribute(DeferInterface::class);
   $defer = $request->getAttribute('custom.defer');

Validated retrieval
-------------------

If you already have the middleware instance available, ``getDefer()`` validates
the attribute and throws a ``LogicException`` when it is missing or invalid.

.. code-block:: php

   $defer = $middleware->getDefer($request);

Related pages
-------------

- :doc:`../advanced/middleware`
- :doc:`../api/middleware`
