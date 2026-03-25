<?php

declare(strict_types=1);

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';

/** @var Kernel $kernel */
$kernel = $app->make(Kernel::class);

$request = Request::create('/admin/pembayaran/data', 'GET', [
    'draw' => 1,
    'start' => 0,
    'length' => 10,
    'status' => 'all',
], [], [], [
    'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
]);

$response = $kernel->handle($request);

echo 'HTTP: '.$response->getStatusCode().PHP_EOL;
echo substr((string) $response->getContent(), 0, 800).PHP_EOL;
