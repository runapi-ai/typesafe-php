<?php

declare(strict_types=1);

namespace RunApi\Typesafe;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\Typesafe\Resources\SystemOne;

/**
 * TypeSafe RunAPI PHP client.
 *
 * The client exposes typed model resources plus the universal `files` and
 * `account` resources.
 */
final class TypesafeClient extends BaseClient
{
    /** System one operations for TypeSafe. */
    public readonly SystemOne $systemOne;

    /** Create a TypeSafe client with optional API key, base URL, and transport overrides. */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->systemOne = SystemOne::fromHttp($this->http);
    }
}
