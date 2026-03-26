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
use FastForward\Defer\ErrorReporter\CompositeErrorReporter;
use FastForward\Defer\ErrorReporterInterface;

#[CoversClass(CompositeErrorReporter::class)]
final class CompositeErrorReporterTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function reportWithThrowableWillCallAllReporters(): void
    {
        $reporter1 = new class implements ErrorReporterInterface {
            public int $calls = 0;

            /**
             * @param Throwable $throwable
             * @param callable|null $callback
             * @param array $arguments
             *
             * @return void
             */
            public function report(Throwable $throwable, ?callable $callback = null, array $arguments = []): void
            {
                ++$this->calls;
            }
        };
        $reporter2 = new class implements ErrorReporterInterface {
            public int $calls = 0;

            /**
             * @param Throwable $throwable
             * @param callable|null $callback
             * @param array $arguments
             *
             * @return void
             */
            public function report(Throwable $throwable, ?callable $callback = null, array $arguments = []): void
            {
                ++$this->calls;
            }
        };
        $composite = new CompositeErrorReporter($reporter1, $reporter2);
        $composite->report(new Exception('fail'));
        self::assertSame(1, $reporter1->calls);
        self::assertSame(1, $reporter2->calls);
    }

    /**
     * @return void
     */
    #[Test]
    public function addWithReporterWillChangeCountAndIsEmpty(): void
    {
        $composite = new CompositeErrorReporter();
        self::assertTrue($composite->isEmpty());
        self::assertSame(0, $composite->count());
        $reporter = new class implements ErrorReporterInterface {
            /**
             * @param Throwable $throwable
             * @param callable|null $callback
             * @param array $arguments
             *
             * @return void
             */
            public function report(Throwable $throwable, ?callable $callback = null, array $arguments = []): void {}
        };
        $composite->add($reporter);
        self::assertFalse($composite->isEmpty());
        self::assertSame(1, $composite->count());
    }

    /**
     * @return void
     *
     * @throws RuntimeException
     */
    #[Test]
    public function reportWithFailingReporterWillLogError(): void
    {
        $failingReporter = new class implements ErrorReporterInterface {
            /**
             * @param Throwable $throwable
             * @param callable|null $callback
             * @param array $arguments
             *
             * @return void
             *
             * @throws RuntimeException
             */
            public function report(Throwable $throwable, ?callable $callback = null, array $arguments = []): void
            {
                throw new RuntimeException('Reporter failed');
            }
        };
        $workingReporter = new class implements ErrorReporterInterface {
            public int $calls = 0;

            /**
             * @param Throwable $throwable
             * @param callable|null $callback
             * @param array $arguments
             *
             * @return void
             */
            public function report(Throwable $throwable, ?callable $callback = null, array $arguments = []): void
            {
                ++$this->calls;
            }
        };

        $composite = new CompositeErrorReporter($failingReporter, $workingReporter);

        // Captura o error_log
        $logFile = sys_get_temp_dir() . '/defer_composite_error_log.txt';
        ini_set('error_log', $logFile);
        if (file_exists($logFile)) {
            unlink($logFile);
        }

        $composite->report(new Exception('fail'));

        $logContent = file_get_contents($logFile);
        self::assertIsString($logContent);
        self::assertStringContainsString('Composite reporter failed', $logContent);
        self::assertStringContainsString('Reporter failed', $logContent);
        self::assertSame(1, $workingReporter->calls);

        // Limpa
        unlink($logFile);
    }
}
