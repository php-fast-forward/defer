Middleware API
==============

``DeferMiddleware``
-------------------

``FastForward\Defer\Middleware\DeferMiddleware`` implements
``Psr\Http\Server\MiddlewareInterface`` and provides request-scoped cleanup.

Constructor
~~~~~~~~~~~

.. code-block:: php

   public function __construct(string $attribute = DeferInterface::class)

The constructor controls the request attribute name used to store the scope.

Public methods
~~~~~~~~~~~~~~

``getAttribute(): string``
   Returns the configured request attribute name.

``getDefer(ServerRequestInterface $request): DeferInterface``
   Reads the configured attribute and validates that it contains a
   ``DeferInterface`` implementation. Throws ``LogicException`` otherwise.

``process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface``
   Creates a new ``Defer`` instance, injects it into the request, calls the next
   handler, and unsets the scope in a ``finally`` block.

Behavior summary
----------------

- One middleware invocation creates one new ``Defer`` scope.
- The scope is request-local.
- Cleanup runs whether the handler returns normally or throws.
