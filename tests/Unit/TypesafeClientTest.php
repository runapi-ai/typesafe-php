<?php

declare(strict_types=1);

namespace RunApi\Typesafe\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Resources\Pricing;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\Typesafe\Models\SystemOneResponse;
use RunApi\Typesafe\Resources\SystemOne;
use RunApi\Typesafe\TypesafeClient;

final class TypesafeClientTest extends TestCase
{
    public function testExposesTypedSynchronousResource(): void
    {
        $client = new TypesafeClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(SystemOne::class, $client->systemOne);
        self::assertInstanceOf(Pricing::class, $client->pricing);
    }

    public function testRunPostsOnlyPublicParamsAndReturnsTypedResponse(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"model":"jev-1.13.0","answers":{"recommendation":{"type":"choice","choice":"Option A","probabilities":{"Option A":0.88,"Option B":0.12},"confidence":0.81}},"usage":{"input_tokens":318,"output_tokens":34},"extra_field":"kept"}'),
        ]);
        $client = new TypesafeClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->systemOne->run([
            'model' => 'jev-latest',
            'state' => ['candidate' => 'Option A'],
            'questions' => ['recommendation' => ['type' => 'choice', 'instructions' => 'Choose the matching candidate.', 'criteria' => ['Option A' => 'The candidate is Option A.', 'Option B' => 'The candidate is Option B.']]],
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertInstanceOf(SystemOneResponse::class, $result);
        self::assertSame(['recommendation' => ['type' => 'choice', 'choice' => 'Option A', 'probabilities' => ['Option A' => 0.88, 'Option B' => 0.12], 'confidence' => 0.81]], $result->answers);
        self::assertSame('jev-1.13.0', $result->toArray()['model']);
        self::assertSame(['input_tokens' => 318, 'output_tokens' => 34], $result->toArray()['usage']);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame([
            'model' => 'jev-latest',
            'state' => ['candidate' => 'Option A'],
            'questions' => ['recommendation' => ['type' => 'choice', 'instructions' => 'Choose the matching candidate.', 'criteria' => ['Option A' => 'The candidate is Option A.', 'Option B' => 'The candidate is Option B.']]],
        ], $body);
        self::assertSame('/api/v1/typesafe/system_one', $transport->requests[0]->getUri()->getPath());
    }

    public function testRunRequiresAnswersObject(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"answers":"not-an-object"}'),
        ]);
        $client = new TypesafeClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('answers must be an object');

        $client->systemOne->run([
            'model' => 'jev-latest',
            'state' => ['candidate' => 'Option A'],
            'questions' => ['recommendation' => ['type' => 'choice', 'instructions' => 'Choose the matching candidate.', 'criteria' => ['Option A' => 'The candidate is Option A.', 'Option B' => 'The candidate is Option B.']]],
        ]);
    }
}
