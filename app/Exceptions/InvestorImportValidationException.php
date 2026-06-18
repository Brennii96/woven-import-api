<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class InvestorImportValidationException extends RuntimeException
{
    public static function forFile(string $message): self
    {
        return new self($message);
    }

    public static function forRow(int $lineNumber, string $message): self
    {
        return new self("Row {$lineNumber}: {$message}");
    }

    public function errors(): array
    {
        return ['file' => [$this->getMessage()]];
    }
}
