<?php
// Mock course data - no database required
$courses = [
    [
        'id' => 1,
        'title' => 'PHP Programming Fundamentals',
        'description' => 'Learn the basics of PHP programming language',
        'duration' => '4 hours',
        'level' => 'Beginner',
        'instructor' => 'John Smith',
        'price' => 49.99,
        'rating' => 4.5,
        'region' => $_ENV['APP_REGION'] ?? 'global'
    ],
    [
        'id' => 2,
        'title' => 'AWS DevOps Essentials',
        'description' => 'Master DevOps practices on AWS platform',
        'duration' => '6 hours',
        'level' => 'Intermediate',
        'instructor' => 'Sarah Johnson',
        'price' => 79.99,
        'rating' => 4.7,
        'region' => $_ENV['APP_REGION'] ?? 'global'
    ],
    [
        'id' => 3,
        'title' => 'Docker and Kubernetes',
        'description' => 'Containerization and orchestration with Docker & Kubernetes',
        'duration' => '5 hours',
        'level' => 'Intermediate',
        'instructor' => 'Mike Chen',
        'price' => 69.99,
        'rating' => 4.8,
        'region' => $_ENV['APP_REGION'] ?? 'global'
    ],
    [
        'id' => 4,
        'title' => 'React Frontend Development',
        'description' => 'Build modern web applications with React',
        'duration' => '7 hours',
        'level' => 'Beginner',
        'instructor' => 'Emily Davis',
        'price' => 59.99,
        'rating' => 4.6,
        'region' => $_ENV['APP_REGION'] ?? 'global'
    ],
    [
        'id' => 5,
        'title' => 'Microservices Architecture',
        'description' => 'Design and build scalable microservices',
        'duration' => '8 hours',
        'level' => 'Advanced',
        'instructor' => 'Robert Brown',
        'price' => 89.99,
        'rating' => 4.9,
        'region' => $_ENV['APP_REGION'] ?? 'global'
    ]
];

// Check if specific course is requested
if (isset($courseId)) {
    $course = array_filter($courses, function($course) use ($courseId) {
        return $course['id'] === $courseId;
    });
    
    if (!empty($course)) {
        echo json_encode([
            'status' => 'success',
            'data' => array_values($course)[0]
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Course not found'
        ]);
    }
} else {
    // Return all courses
    echo json_encode([
        'status' => 'success',
        'count' => count($courses),
        'data' => $courses,
        'region' => $_ENV['APP_REGION'] ?? 'global'
    ]);
}
?>
