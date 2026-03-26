Helper Functions
================

**defer()**

Creates a new Defer instance and returns it.

.. code-block:: php

   use function FastForward\Defer\defer;
   $defer = defer();
   $defer(fn() => print "cleanup via helper\n");

**scope()**

Runs a block with an isolated defer scope.

.. code-block:: php

   use function FastForward\Defer\scope;
   scope(function ($defer) {
       $defer(fn() => print "Cleanup\n");
       echo "Inside\n";
   });

**using()**

Structured resource management (acquire → use → cleanup):

.. code-block:: php

   use function FastForward\Defer\using;
   using(
       function ($defer) {
           $file = fopen('file.txt', 'w+');
           $defer(fn() => fclose($file));
           return $file;
       },
       function ($file) {
           fwrite($file, "Hello");
       }
   );


Design Principles
-----------------

- Deterministic cleanup
- Minimal API
- No manual flush
- Failure isolation
- Extensible reporting

Notes
-----

- Execution is triggered by `__destruct()`