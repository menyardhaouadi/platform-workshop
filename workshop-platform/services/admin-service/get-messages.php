<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once 'db.php';
$conn = getDB();
$res = $conn->query('SELECT m.*,u.name as sender_name,u.email as sender_email FROM messages m JOIN users u ON m.sender_id=u.id ORDER BY m.created_at DESC');
$list = [];
while ($row = $res->fetch_assoc()) $list[] = $row;
echo json_encode(['success'=>true,'messages'=>$list]);
$conn->close();
