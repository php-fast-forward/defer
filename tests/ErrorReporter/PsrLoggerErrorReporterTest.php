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

use Prophecy\Prophecy\ObjectProphecy;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use FastForward\Defer\ErrorReporter\PsrLoggerErrorReporter;
use FastForward\Defer\Support\CallbackDescriber;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use Prophecy\Argument;

#[CoversClass(PsrLoggerErrorReporter::class)]
#[UsesClass(CallbackDescriber::class)]
final class PsrLoggerErrorReporterTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy $logger;

    private PsrLoggerErrorReporter $reporter;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->reporter = new PsrLoggerErrorReporter($this->logger->reveal());
    }

    /**
     * @return void
     */
    #[Test]
    public function reportWithThrowableWillLogError(): void
    {
        $throwable = new Exception('fail');

        $this->logger->error(
            Argument::containingString('Deferred callback failed:'),
            Argument::that(
                fn($context): bool => isset($context['exception_class']) && 'Exception' === $context['exception_class']
            )
        )->shouldBeCalledOnce();

        $this->reporter->report($throwable, fn(): null => null);
    }
}
