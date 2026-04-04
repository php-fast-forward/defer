Getting Started
===============

If you are new to defer-style cleanup, start here. The central idea is simple:
create a scope object, register cleanup callbacks while you work, and let the
scope end naturally. When that scope ends, callbacks run in reverse order of
registration.

Why this package exists
-----------------------

In plain PHP, cleanup usually ends up far away from resource acquisition:

- open a file now, remember to close it later
- start buffering now, remember to flush or discard it later
- allocate a temporary file now, remember to delete it later

``fast-forward/defer`` keeps those steps close together while still preserving
correct teardown order.

Minimal example
---------------

.. code-block:: php

   use FastForward\Defer\Defer;

   function run(): void
   {
       $defer = new Defer();

       $defer(fn(): int => print "close connection\n");
       $defer(fn(): int => print "flush buffer\n");

       echo "doing work\n";
   }

   run();

.. code-block:: text

   doing work
   flush buffer
   close connection

Three rules to remember
-----------------------

- Register callbacks in the reverse order of the teardown you want.
- Keep ``Defer`` instances short-lived and scope-bound.
- If you need cleanup at a precise point, use ``scope()`` or explicitly
  ``unset($defer)`` instead of waiting until script shutdown.

Choose your first entry point
-----------------------------

.. list-table::
   :header-rows: 1

   * - Entry point
     - Use it when
   * - ``new Defer()``
     - You already have a natural local scope such as a function, command, or
       request handler.
   * - ``defer()``
     - You want the same behavior with slightly shorter syntax.
   * - ``scope()``
     - You want an explicit temporary scope inside a larger function.
   * - ``using()``
     - You want a compact acquire -> use -> cleanup workflow.

Where to go next
----------------

.. toctree::
   :maxdepth: 1

   installation
   usage
   examples/index
   faq
