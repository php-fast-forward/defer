Dependencies
============

This page summarizes the package dependencies that matter most when you adopt
``fast-forward/defer``.

Direct runtime dependencies
---------------------------

.. list-table::
   :header-rows: 1

   * - Package
     - Why it is present
   * - ``php``
     - The library requires PHP 8.3 or newer
   * - ``fast-forward/container``
     - Part of the Fast Forward ecosystem dependency graph used by this package
   * - ``psr/http-server-middleware``
     - Provides the PSR-15 middleware interface implemented by
       ``DeferMiddleware``

Resolved runtime tree highlights
--------------------------------

According to the current Composer lock file, the runtime dependency graph also
pulls in these notable packages transitively:

- ``psr/container``
- ``container-interop/service-provider``
- ``fast-forward/config``
- ``php-di/php-di``
- ``psr/http-message``
- ``psr/http-server-handler``

Most applications will not interact with those packages directly when using the
basic defer API, but they are part of the installed graph.

Optional integration dependencies
---------------------------------

Depending on which integration path you choose, your application may also need
concrete implementations for:

- PSR-3 logging
- PSR-14 event dispatching
- PSR-7 request/response objects when running HTTP examples or middleware tests

Development dependencies in this repository
-------------------------------------------

The repository itself also declares development-only packages such as:

- ``fast-forward/dev-tools``
- ``fast-forward/http-message``
- PHPUnit and Prophecy-related packages from the resolved development graph

Those are useful for tests, examples, and maintenance workflows, but they are
not required by consumers for basic library usage.
