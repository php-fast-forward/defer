FastForward Defer
=================

``fast-forward/defer`` brings defer-style cleanup to PHP by tying callbacks to
object lifetime. A ``Defer`` instance collects callbacks and runs them
automatically when the scope ends, which makes cleanup code easier to keep next
to acquisition code without losing predictable execution order.

The package is intentionally small. You get a concrete ``Defer`` class, a
minimal ``DeferInterface``, three helper functions, pluggable error reporting,
PSR-15 middleware support, and a utility to describe callbacks for logs and
events.

Useful links
------------

- `Repository <https://github.com/php-fast-forward/defer>`_
- `Packagist <https://packagist.org/packages/fast-forward/defer>`_
- `Issue Tracker <https://github.com/php-fast-forward/defer/issues>`_
- `README <https://github.com/php-fast-forward/defer/blob/main/README.md>`_
- `Tests Workflow <https://github.com/php-fast-forward/defer/actions/workflows/tests.yml>`_
- `Reports Workflow <https://github.com/php-fast-forward/defer/actions/workflows/reports.yml>`_

Highlights
----------

- Scope-bound cleanup using direct instantiation or helper functions.
- LIFO execution, which keeps teardown order explicit and deterministic.
- Exception-safe callback execution: one failing callback does not stop the
  callback itself from being reported.
- Pluggable error reporters, including ``error_log``, PSR-3, PSR-14, and
  composite fan-out.
- Request-scoped cleanup with ``DeferMiddleware`` for PSR-15 applications.
- ``CallbackDescriber`` for readable log and event context.

.. toctree::
   :maxdepth: 2
   :caption: Contents:

   getting-started
   installation
   usage
   advanced/index
   api/index
   psr/index
   examples/index
   links/index
   faq
   compatibility
