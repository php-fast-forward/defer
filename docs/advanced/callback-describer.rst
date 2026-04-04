Callback Describer
==================

``CallbackDescriber`` converts a PHP callable into a human-readable string.
Built-in reporters use it so logs and events can tell you which deferred
callback failed.

Why it exists
-------------

Without a helper like this, generic error reporters would only know that "some
callable" failed. ``CallbackDescriber`` adds context such as:

- the function name
- the class and method
- the closure source file and line
- whether the callback was an invokable object

Supported callable shapes
-------------------------

- String function names such as ``'strlen'``
- Array callables such as ``[SomeClass::class, 'handle']``
- Closures
- First-class callables, which are represented as closures
- Invokable objects

.. code-block:: php

   use FastForward\Defer\Support\CallbackDescriber;

   final class ExampleInvoker
   {
       public static function staticHandle(): void {}

       public function handle(): void {}

       public function __invoke(): void {}
   }

   $object = new ExampleInvoker();

   $callbacks = [
       'strlen',
       [ExampleInvoker::class, 'staticHandle'],
       [$object, 'handle'],
       static fn(): null => null,
       ExampleInvoker::staticHandle(...),
       $object,
   ];

   foreach ($callbacks as $callback) {
       echo CallbackDescriber::describe($callback) . "\n";
   }

What to expect from the output
------------------------------

- String functions stay unchanged.
- Array callables become ``Class::method`` or ``Class->method``.
- Closures become ``Closure@/path/to/file.php:line``.
- Invokable objects become ``Class::__invoke``.

One subtle but important detail: first-class callables created with ``...`` are
closures, so they are described as closures, not as ``Class::method``. If you
want method-style output, pass an array callable instead.

Where it is used
----------------

- ``ErrorLogErrorReporter``
- ``PsrLoggerErrorReporter``
- ``PsrEventDispatcherErrorReporter``

See :doc:`../api/support` for the compact API reference.
