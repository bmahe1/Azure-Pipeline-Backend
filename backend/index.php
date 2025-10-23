<?php
require_once __DIR__ . '/vendor/autoload.php';

header("Content-Type: application/json");

// Simple routing
$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch (true) {
        case $requestUri === '/api/courses' && $method === 'GET':
            echo json_encode([
                'status' => 'success',
                'data' => [
                    ['id' => 1, 'title' => 'PHP Fundamentals', 'duration' => '4h'],
                    ['id' => 2, 'title' => 'AWS DevOps', 'duration' => '6h']
                ]
            ]);
            break;
            
        case $requestUri === '/api/health' && $method === 'GET':
            echo json_encode(['status' => 'healthy', 'timestamp' => date('c')]);
            break;
            
        case preg_match('#^/api/courses/(\d+)$#', $requestUri, $matches) && $method === 'GET':
            $courseId = $matches[1];
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'id' => $courseId,
                    'title' => 'Course ' . $courseId,
                    'description' => 'Course description here'
                ]
            ]);
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Endpoint not found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Internal server error']);
}
?>
