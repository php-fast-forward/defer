Examples
========

The repository ships a numbered example set so you can learn the package in a
progressive order. Run examples from the repository root:

.. code-block:: bash

   php examples/01-basic.php

Suggested learning path
-----------------------

.. list-table::
   :header-rows: 1

   * - File
     - Focus
     - What you learn
   * - `01-basic.php <https://github.com/php-fast-forward/defer/blob/main/examples/01-basic.php>`_
     - Basic usage
     - Create a scope and see LIFO cleanup
   * - `02-with-arguments.php <https://github.com/php-fast-forward/defer/blob/main/examples/02-with-arguments.php>`_
     - Callback arguments
     - Store data together with cleanup logic
   * - `03-lifo-order.php <https://github.com/php-fast-forward/defer/blob/main/examples/03-lifo-order.php>`_
     - Ordering
     - Register teardown in reverse order
   * - `04-exception-safety.php <https://github.com/php-fast-forward/defer/blob/main/examples/04-exception-safety.php>`_
     - Exceptions
     - Cleanup still runs when main code fails
   * - `05-nested-scopes.php <https://github.com/php-fast-forward/defer/blob/main/examples/05-nested-scopes.php>`_
     - Nested scopes
     - Inner cleanup completes before outer cleanup
   * - `06-helper-defer.php <https://github.com/php-fast-forward/defer/blob/main/examples/06-helper-defer.php>`_
     - ``defer()``
     - Create a scope with the helper function
   * - `07-helper-scope.php <https://github.com/php-fast-forward/defer/blob/main/examples/07-helper-scope.php>`_
     - ``scope()``
     - Force a cleanup boundary inside a callback
   * - `08-helper-using.php <https://github.com/php-fast-forward/defer/blob/main/examples/08-helper-using.php>`_
     - ``using()``
     - Model acquire -> use -> cleanup
   * - `09-custom-error-reporter.php <https://github.com/php-fast-forward/defer/blob/main/examples/09-custom-error-reporter.php>`_
     - Custom reporting
     - Implement ``ErrorReporterInterface``
   * - `10-null-error-reporter.php <https://github.com/php-fast-forward/defer/blob/main/examples/10-null-error-reporter.php>`_
     - Silent reporting
     - Suppress cleanup failures
   * - `11-composite-error-reporter.php <https://github.com/php-fast-forward/defer/blob/main/examples/11-composite-error-reporter.php>`_
     - Reporter fan-out
     - Send the same failure to multiple sinks
   * - `12-psr-logger-error-reporter.php <https://github.com/php-fast-forward/defer/blob/main/examples/12-psr-logger-error-reporter.php>`_
     - PSR-3
     - Log failures with structured context
   * - `13-psr14-event-dispatcher.php <https://github.com/php-fast-forward/defer/blob/main/examples/13-psr14-event-dispatcher.php>`_
     - PSR-14
     - Dispatch a failure event instead of logging directly
   * - `14-http-middleware.php <https://github.com/php-fast-forward/defer/blob/main/examples/14-http-middleware.php>`_
     - PSR-15
     - Bind a defer scope to a request lifecycle
   * - `15-callback-describer.php <https://github.com/php-fast-forward/defer/blob/main/examples/15-callback-describer.php>`_
     - Utility support
     - Inspect how different callable forms are described

Notes
-----

- Example ``14-http-middleware.php`` relies on the development dependencies used
  by this repository for HTTP message objects.
- The examples are intentionally small and map closely to the topics in
  :doc:`../usage` and :doc:`../advanced/index`.
