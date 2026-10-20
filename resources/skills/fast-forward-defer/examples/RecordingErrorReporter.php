<?php

declare(strict_types=1);

namespace Example\Defer;

use FastForward\Defer\ErrorReporterInterface;
use Throwable;

final class RecordingErrorReporter implements ErrorReporterInterface
{
    /** @var list<Throwable> */
    public array $errors = [];

    public function report(Throwable $throwable, ?callable $callback = null, array $arguments = []): void
    {
        $this->errors[] = $throwable;
    }
}
