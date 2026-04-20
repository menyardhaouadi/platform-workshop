<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once 'db.php';
$conn = getDB();
$res = $conn->query('SELECT w.*,(SELECT COUNT(*) FROM registrations r WHERE r.workshop_id=w.id AND r.status="registered") as student_count FROM workshops w ORDER BY w.date ASC');
$list = [];
while ($row = $res->fetch_assoc()) { $row['student_count']=(int)$row['student_count']; $list[]=$row; }
echo json_encode(['success'=>true,'workshops'=>$list]);
$conn->close();
