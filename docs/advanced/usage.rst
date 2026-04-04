Scope Patterns And Lifetime Control
===================================

The library is tiny, but lifetime semantics matter. This page focuses on when
to choose each entry point and how to keep destruction timing obvious.

Choose the right entry point
----------------------------

.. list-table::
   :header-rows: 1

   * - API
     - Best fit
     - Notes
   * - ``new Defer()``
     - A local function, method, command, or request handler
     - Most explicit and easiest to debug
   * - ``defer()``
     - The same situations as ``new Defer()``
     - Thin helper that returns a ``DeferInterface``
   * - ``scope()``
     - A temporary inner scope inside a larger workflow
     - Cleanup runs when the callback finishes
   * - ``using()``
     - Resource acquisition with co-located cleanup
     - Good for files, temporary buffers, and disposable handles

Using the ``defer()`` helper
----------------------------

.. code-block:: php

   use FastForward\Defer\DeferInterface;
   use function FastForward\Defer\defer;

   /** @var DeferInterface $defer */
   $defer = defer();
   $defer(fn(): int => print "cleanup via helper\n");

This helper does not add new behavior. It is only a shorter way to create a new
scope object.

Creating a nested cleanup boundary with ``scope()``
---------------------------------------------------

.. code-block:: php

   use FastForward\Defer\DeferInterface;
   use function FastForward\Defer\scope;

   function run(): void
   {
       echo "outer start\n";

       scope(static function (DeferInterface $defer): void {
           $defer(fn(): int => print "inner cleanup 1\n");
           $defer(fn(): int => print "inner cleanup 2\n");

           echo "inside inner scope\n";
       });

       echo "outer end\n";
   }

Nested scopes stay isolated. An inner scope always resolves before the outer
scope that created it.

Using ``using()`` for structured resource management
----------------------------------------------------

.. code-block:: php

   use FastForward\Defer\DeferInterface;
   use function FastForward\Defer\using;

   using(
       static function (DeferInterface $defer) {
           $filename = __DIR__ . '/temporary.txt';
           $file = fopen($filename, 'w+');

           if (false === $file) {
               throw new RuntimeException('Unable to open temporary file.');
           }

           $defer(static fn(): bool => unlink($filename));
           $defer(static fn(): bool => fclose($file));

           return $file;
       },
       static function ($file): void {
           fwrite($file, "hello from using()\n");
       },
   );

The factory callback acquires the resource and registers cleanup. The second
callback focuses on actual work.

Destruction timing matters
--------------------------

``Defer`` has no public ``flush()`` method. Cleanup happens when the object is
destroyed.

That is ideal for:

- short CLI commands
- request/response handlers
- one-job-per-iteration worker loops
- temporary inner scopes

That is risky for:

- long-lived singleton services
- static properties
- objects stored globally and released only at script shutdown

If the timing feels ambiguous, prefer one of these approaches:

- keep the ``Defer`` variable local to a small function
- wrap work in ``scope()``
- explicitly ``unset($defer)``

Design principles
-----------------

- Deterministic cleanup
- Minimal public API
- No manual flush operation
- Short-lived scope objects over hidden global state
- Pluggable reporting instead of throwing from cleanup callbacks
