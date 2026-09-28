<?php

declare(strict_types=1);

namespace RunApi\Typesafe\Models;

use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Models\BaseModel;

/** Response returned by system one. */
readonly class SystemOneResponse extends BaseModel
{
    /**
     * @param array<string, mixed> $answers
     * @param array<string, mixed> $raw Raw response payload preserved by `toArray()`.
     */
    public function __construct(public array $answers, array $raw = [])
    {
        parent::__construct($raw === [] ? ['answers' => $answers] : $raw);
    }

    /**
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        $value = $raw['answers'] ?? null;
        if (!is_array($value)) {
            throw new ValidationException('answers must be an object');
        }

        return new self(answers: $value, raw: $raw);
    }
}
