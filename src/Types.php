<?php

declare(strict_types=1);

namespace RunApi\Typesafe;

final class Types
{
    /**
     * Allowed model slugs for system one requests.
     *
     * @var list<string>
     */
    public const SYSTEM_ONE_MODELS = ['jev-latest'];

    private function __construct()
    {
    }
}
