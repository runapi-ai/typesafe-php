<?php

declare(strict_types=1);

namespace RunApi\Typesafe\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\RequestOptions;
use RunApi\Typesafe\Models\SystemOneResponse;

/** System one operations for TypeSafe. */
readonly class SystemOne extends SyncResource
{
    /**
     * Run system one and return its response.
     *
     * @param array{
     *   model: string,
     *   questions: array<string, mixed>,
     *   state: string|array<array-key, mixed>
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): SystemOneResponse
    {
        $response = parent::run($params, $options);

        /** @var SystemOneResponse $response */
        return $response;
    }

    /** Create the resource using the shared RunAPI HTTP transport. */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/typesafe/system_one',
            'typesafe/system-one',
            SystemOneResponse::class,
        );
    }
}
