<?php

declare(strict_types=1);

/**
 * This file is part of fast-forward/defer.
 *
 * This source file is subject to the license bundled
 * with this source code in the file LICENSE.
 *
 * @copyright Copyright (c) 2026 Felipe Sayão Lobato Abreu <github@mentordosnerds.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 *
 * @see       https://github.com/php-fast-forward/defer
 * @see       https://github.com/php-fast-forward
 * @see       https://datatracker.ietf.org/doc/html/rfc2119
 */

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use FastForward\Defer\ErrorReporter\ErrorLogErrorReporter;
use FastForward\Defer\Support\CallbackDescriber;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(ErrorLogErrorReporter::class)]
#[UsesClass(CallbackDescriber::class)]
final class ErrorLogErrorReporterTest extends TestCase
{
    private ErrorLogErrorReporter $reporter;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->reporter = new ErrorLogErrorReporter();
    }

    /**
     * @return void
     */
    #[Test]
    public function reportWithThrowableWillOutputError(): void
    {
        $this->expectOutputRegex('/fail/');
        $this->reporter->report(new Exception('fail'), function (): void {});
    }
}
