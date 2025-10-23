<?php
// Simple health check without database
$status = [
    'status' => 'healthy',
    'timestamp' => date('c'),
    'service' => 'knowledgecity-backend',
    'region' => $_ENV['APP_REGION'] ?? 'unknown',
    'checks' => [
        'disk_space' => check_disk_space(),
        'memory_usage' => get_memory_usage(),
        'php_version' => PHP_VERSION
    ]
];

http_response_code(200);
echo json_encode($status);

function check_disk_space() {
    $free = disk_free_space("/");
    $total = disk_total_space("/");
    return [
        'free' => round($free / 1024 / 1024, 2) . ' MB',
        'total' => round($total / 1024 / 1024, 2) . ' MB',
        'healthy' => $free > 100 * 1024 * 1024 // 100MB minimum
    ];
}

function get_memory_usage() {
    $usage = memory_get_usage(true);
    $peak = memory_get_peak_usage(true);
    return [
        'current' => round($usage / 1024 / 1024, 2) . ' MB',
        'peak' => round($peak / 1024 / 1024, 2) . ' MB',
        'healthy' => $usage < 128 * 1024 * 1024 // 128MB limit
    ];
}
?>
