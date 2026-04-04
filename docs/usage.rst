Usage
=====

This page covers the everyday usage patterns most applications need. If you are
new to the library, read this page from top to bottom before moving to
:doc:`advanced/index`.

Create a scope and register callbacks
-------------------------------------

.. code-block:: php

   use FastForward\Defer\Defer;

   function run(): void
   {
       $defer = new Defer();

       $defer(fn(): int => print "disconnect\n");
       $defer->defer(fn(): int => print "flush\n");

       echo "work in progress\n";
   }

   run();

.. code-block:: text

   work in progress
   flush
   disconnect

Both ``$defer(...)`` and ``$defer->defer(...)`` register callbacks. Use
whichever is clearer in your codebase.

Pass callback arguments explicitly
----------------------------------

Arguments are stored together with the callback and replayed later when cleanup
runs.

.. code-block:: php

   use FastForward\Defer\Defer;

   function run(): void
   {
       $defer = new Defer();

       $defer(
           static function (string $filename): void {
               echo sprintf("removing %s\n", $filename);
           },
           'temp.txt',
       );

       echo "working\n";
   }

   run();

.. code-block:: text

   working
   removing temp.txt

Understand the LIFO rule
------------------------

Deferred callbacks run in **Last In, First Out** order. Register cleanup in the
reverse order of the teardown you want.

.. code-block:: php

   $defer(fn(): bool => unlink($filename));
   $defer(fn(): bool => fclose($file));

The file handle closes first, then the file is removed. That order is correct,
because deleting the file before closing the handle would be unsafe or at least
confusing on many systems.

Exception safety
----------------

The scope still unwinds when your main code throws:

.. code-block:: php

   use FastForward\Defer\Defer;

   function run(): void
   {
       $defer = new Defer();
       $defer(fn(): int => print "cleanup\n");

       throw new RuntimeException('something failed');
   }

   try {
       run();
   } catch (RuntimeException $exception) {
       echo $exception->getMessage() . "\n";
   }

.. code-block:: text

   cleanup
   something failed

If one deferred callback throws, ``Defer`` catches that failure and forwards it
to the configured error reporter. See :doc:`advanced/error-reporting`.

Force cleanup at an explicit boundary with ``scope()``
------------------------------------------------------

Use ``scope()`` when a function is large but only part of it should have a local
cleanup boundary.

.. code-block:: php

   use FastForward\Defer\DeferInterface;
   use function FastForward\Defer\scope;

   function run(): void
   {
       echo "before scoped work\n";

       scope(static function (DeferInterface $defer): void {
           $defer(fn(): int => print "scoped cleanup\n");
           echo "inside scoped work\n";
       });

       echo "after scoped work\n";
   }

.. code-block:: text

   before scoped work
   inside scoped work
   scoped cleanup
   after scoped work

Model acquire -> use -> cleanup with ``using()``
------------------------------------------------

``using()`` is a convenient pattern when a resource factory also wants to
declare its own cleanup.

.. code-block:: php

   use FastForward\Defer\DeferInterface;
   use function FastForward\Defer\using;

   using(
       static function (DeferInterface $defer) {
           $file = fopen('php://temp', 'w+');

           if (false === $file) {
               throw new RuntimeException('Unable to create temporary stream.');
           }

           $defer(static fn(): bool => fclose($file));

           return $file;
       },
       static function ($file): void {
           fwrite($file, "hello\n");
       },
   );

This style keeps acquisition and release together while leaving business logic
in the second callback.

Inspect the current stack
-------------------------

``Defer`` implements ``Countable`` and exposes ``isEmpty()``.

.. code-block:: php

   use FastForward\Defer\Defer;

   $defer = new Defer();
   var_dump($defer->isEmpty()); // true
   var_dump($defer->count());   // 0

   $defer(fn(): null => null);

   var_dump($defer->isEmpty()); // false
   var_dump(count($defer));     // 1

This is mainly useful in tests, diagnostics, or when you want to guard an
optional cleanup path.

Common beginner mistakes
------------------------

- Keeping one ``Defer`` instance in a long-lived singleton or static property.
- Forgetting that cleanup happens on destruction, not immediately after
  registration.
- Registering callbacks in natural order instead of reverse teardown order.
- Installing the package with the wrong Composer name. Use
  ``fast-forward/defer``.

For lifetime control and long-running process guidance, continue with
:doc:`compatibility` and :doc:`advanced/usage`.
