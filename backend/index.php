<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Simple router
try {
    switch (true) {
        case $requestUri === '/health' && $method === 'GET':
            require_once 'health.php';
            break;
            
        case $requestUri === '/api/courses' && $method === 'GET':
            require_once 'api/courses.php';
            break;
            
        case preg_match('#^/api/courses/(\d+)$#', $requestUri, $matches) && $method === 'GET':
            $courseId = intval($matches[1]);
            require_once 'api/courses.php';
            break;
            
        case $requestUri === '/api/status' && $method === 'GET':
            echo json_encode([
                'status' => 'success',
                'service' => 'KnowledgeCity Backend',
                'region' => $_ENV['APP_REGION'] ?? 'unknown',
                'timestamp' => date('c'),
                'version' => '1.0.0'
            ]);
            break;
            
        default:
            http_response_code(404);
            echo json_encode([
                'status' => 'error', 
                'message' => 'Endpoint not found',
                'path' => $requestUri
            ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Internal server error'
    ]);
}
?>
