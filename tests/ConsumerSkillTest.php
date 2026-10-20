<?php

declare(strict_types=1);

/**
 * Fast Forward Deferred Callbacks utility classes.
 *
 * This file is part of fast-forward/defer project.
 *
 * @author   Felipe Sayão Lobato Abreu <github@mentordosnerds.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 *
 * @see      https://php-fast-forward.github.io/defer/
 * @see      https://github.com/php-fast-forward/defer
 * @see      https://github.com/php-fast-forward/defer/issues
 * @see      https://datatracker.ietf.org/doc/html/rfc2119
 */

namespace FastForward\Defer\Tests;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

#[CoversNothing]
final class ConsumerSkillTest extends TestCase
{
    /**
     * @return void
     */
    public function testBundledConsumerIntegration(): void
    {
        $root = \dirname(__DIR__);
        $process = new Process([
            \PHP_BINARY,
            $root . '/resources/skills/fast-forward-defer/examples/integration.php',
            $root . '/vendor/autoload.php',
        ], $root, [
            'XDEBUG_MODE' => 'off',
        ]);
        $process->setTimeout(20);
        $process->run();

        self::assertTrue($process->isSuccessful(), $process->getErrorOutput() . $process->getOutput());
        self::assertStringContainsString('fast-forward-defer: ok', $process->getOutput());
    }
}
