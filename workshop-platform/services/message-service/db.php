<?php
function getDB() {
    $conn = new mysqli('localhost', 'root', '', 'workshop_db');
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'DB error']);
        exit;
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
