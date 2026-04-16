<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiKey = env('GEMINI_API_KEY');
$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $apiKey;
$response = Illuminate\Support\Facades\Http::get($url);
file_put_contents('models_list.json', $response->body());
echo "Models fetched and saved to models_list.json\n";
