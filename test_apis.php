<?php

/**
 * BASE TRAVEL API Verification & Testing Script
 * This script boots Laravel internally and tests all Mobile API V1 endpoints.
 * Run in terminal: php test_apis.php
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 1. Bootstrap Laravel Framework
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Helper to execute internal HTTP requests and print results beautifully
function testEndpoint($method, $uri, $payload = [], $headers = []) {
    global $kernel;

    echo "\n------------------------------------------------------------\n";
    echo "TESTING: [$method] $uri\n";
    if (!empty($payload)) {
        echo "PAYLOAD: " . json_encode($payload, JSON_UNESCAPED_UNICODE) . "\n";
    }
    echo "------------------------------------------------------------\n";

    $server = [
        'HTTP_ACCEPT' => 'application/json',
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
        'HTTP_X_APP_TOKEN' => 'bt_sec_e9b8f2d5c1a4e76f9b8c7d6e5a4f3b2c'
    ];
    foreach ($headers as $key => $val) {
        $server['HTTP_' . strtoupper(str_replace('-', '_', $key))] = $val;
    }

    $content = $method === 'POST' ? json_encode($payload) : null;
    $request = Request::create($uri, $method, [], [], [], $server, $content);
    
    // Dispatch request through Laravel kernel
    $response = $kernel->handle($request);

    echo "STATUS CODE: " . $response->getStatusCode() . "\n";
    
    $body = $response->getContent();
    $data = json_decode($body, true);

    if ($response->isSuccessful() && isset($data['success']) && $data['success']) {
        echo "✔ RESULT: SUCCESS\n";
        echo "MESSAGE: " . ($data['message'] ?? 'OK') . "\n";
        
        // Print clean summary of return structure
        if (isset($data['data'])) {
            $itemCount = is_array($data['data']) ? count($data['data']) : 1;
            if (isset($data['data']['tours'])) {
                $itemCount = count($data['data']['tours']) . " tours";
            } elseif (isset($data['data']['articles'])) {
                $itemCount = count($data['data']['articles']) . " articles";
            }
            echo "DATA SUMMARY: " . (is_array($data['data']) ? "Array/Object with keys [" . implode(', ', array_keys($data['data'])) . "] (" . $itemCount . ")" : gettype($data['data'])) . "\n";
            echo "PREVIEW DATA (first 300 chars):\n" . substr(json_encode($data['data'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), 0, 300) . "...\n";
        }
    } else {
        echo "✘ RESULT: FAILURE\n";
        echo "ERROR RESPONSE:\n" . json_encode($data ?: $body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
    }

    $kernel->terminate($request, $response);
}

echo "=== STARTING API ENDPOINT TESTS ===\n";

// 1. System Metadata
testEndpoint('GET', '/api/v1/system');

// 2. Banner Sliders
testEndpoint('GET', '/api/v1/sliders');

// 3. Tour Categories
testEndpoint('GET', '/api/v1/categories');

// 4. Tour Listing (All)
testEndpoint('GET', '/api/v1/tours');

// 5. Tour Listing (Filtered by Hot)
testEndpoint('GET', '/api/v1/tours?is_hot=true');

// 6. Tour Details (Retrieve first active tour to test dynamically)
$tour = \App\Models\Product::where('status', true)->first();
if ($tour) {
    testEndpoint('GET', "/api/v1/tours/{$tour->slug_vn}");
} else {
    echo "\n[Skip] Tour details test: No active products found.\n";
}

// 7. News Listing
testEndpoint('GET', '/api/v1/news');

// 8. News Detail (Retrieve first active article to test dynamically)
$article = \App\Models\News::where('status', true)->first();
if ($article) {
    testEndpoint('GET', "/api/v1/news/{$article->slug_vn}");
} else {
    echo "\n[Skip] News detail test: No active articles found.\n";
}

// 9. Static Page Detail (Retrieve Page 1 - Giới thiệu)
testEndpoint('GET', '/api/v1/pages/1');

// 10. Contact form Submission
testEndpoint('POST', '/api/v1/contact', [
    'name' => 'Lâm Chấn Huy',
    'email' => 'lamchanhuy@gmail.com',
    'phone' => '0909999888',
    'message' => 'Tôi muốn hỏi tư vấn dịch vụ tour Hạ Long vào tháng 8.'
]);

// 11. Checkout/Order booking Submission
if ($tour) {
    testEndpoint('POST', '/api/v1/checkout', [
        'f_name_order' => 'Nguyễn',
        'l_name_order' => 'Văn Nam',
        'phone' => '0909000111',
        'email' => 'vannam@gmail.com',
        'address' => '39 Tôn Thất Thuyết, Quận 4, TP. HCM',
        'note' => 'Cần phòng có cũi trẻ em',
        'payment_method' => 'transfer',
        'items' => [
            [
                'id_product' => $tour->id_product,
                'quantity' => 2
            ]
        ]
    ]);
} else {
    echo "\n[Skip] Checkout submission test: No active products found.\n";
}

echo "\n=== API TESTS COMPLETED ===\n";
