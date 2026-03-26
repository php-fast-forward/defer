Callback Describer
==================

FastForward Defer includes a utility to describe any PHP callback in a human-readable way.

.. code-block:: php

   use FastForward\Defer\Support\CallbackDescriber;

   $callback = function () {};
   echo CallbackDescriber::describe($callback);

This is useful for debugging, logging, or event reporting.

Supported callback types:

- Named functions
- Static methods
- Object methods
- Closures
- Invokable objects
