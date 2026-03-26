Basic Usage
===========

This section demonstrates the core usage patterns of FastForward Defer, including basic and advanced scenarios.

.. code-block:: php

   use FastForward\Defer\Defer;

   $defer = new Defer();

   $defer(function () {
       echo "First defer\n";
   });

   $defer(function () {
       echo "Second defer\n";
   });

   echo "Inside function\n";

**Output:**

.. code-block:: text

   Inside function
   Second defer
   First defer


Arguments
---------

Deferred callbacks receive the arguments you provide:

.. code-block:: php

   $defer(function ($file) {
       echo "Deleting {$file}\n";
   }, 'temp.txt');

   echo "Working...\n";

**Output:**

.. code-block:: text

   Working...
   Deleting temp.txt


Execution Order
---------------

Callbacks execute in **reverse order of registration** (LIFO):

.. code-block:: php

   $defer(fn() => unlink($file));
   $defer(fn() => fclose($handle));

**Output:**

.. code-block:: text

   fclose($handle)
   unlink($file)

Always register in reverse of the desired execution order.


Exception Safety
----------------

Deferred callbacks always run, even if an exception occurs:

.. code-block:: php

   $defer(fn() => print "Cleanup\n");
   throw new Exception('Failure');

**Output:**

.. code-block:: text

   Cleanup
   Exception caught


Nested Scopes
-------------

Each Defer instance is isolated:

.. code-block:: php

   function outer(): void {
       $defer = new Defer();
       $defer(fn() => print "Outer cleanup\n");
       inner();
   }

   function inner(): void {
       $defer = new Defer();
       $defer(fn() => print "Inner cleanup\n");
   }

   outer();

**Output:**

.. code-block:: text

   Inner cleanup
   Outer cleanup
