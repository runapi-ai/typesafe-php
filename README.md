# TypeSafe PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/typesafe)](https://packagist.org/packages/runapi-ai/typesafe)
[![License](https://img.shields.io/github/license/runapi-ai/typesafe-php)](https://github.com/runapi-ai/typesafe-php/blob/main/LICENSE)

Use the TypeSafe PHP SDK to run structured decisions through RunAPI with a synchronous Composer client.

## Install

```bash
composer require runapi-ai/typesafe
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\Typesafe\TypesafeClient;

$client = new TypesafeClient(); // reads RUNAPI_API_KEY
$result = $client->systemOne->run([
    'model' => 'jev-latest',
    'questions' => ['recommendation' => [
        'type' => 'choice',
        'instructions' => 'Choose the matching candidate.',
        'criteria' => ['Option A' => 'The candidate is Option A.', 'Option B' => 'The candidate is Option B.'],
    ]],
    'state' => ['candidate' => 'Option A'],
]);

echo json_encode($result->answers) . PHP_EOL;
```

Pass request parameters as associative arrays with snake_case keys. Keep
`RUNAPI_API_KEY` in the environment or your secret manager.

## Links

- Model page: https://runapi.ai/models/jev
- SDK docs: https://runapi.ai/docs/resources/sdks
- Product docs: https://runapi.ai/docs/api/typesafe/system-one
- Pricing and rate limits: https://runapi.ai/models/jev/jev-latest
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/typesafe-php
- Multi-language SDK repository: https://github.com/runapi-ai/typesafe-sdk

## License

Licensed under the Apache License, Version 2.0.
