<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once 'db.php';
$d = json_decode(file_get_contents('php://input'), true);
$uid = intval($d['user_id'] ?? 0);
if (!$uid) { echo json_encode(['success'=>false,'message'=>'User ID required']); exit; }
$conn = getDB();
$st = $conn->prepare('SELECT w.*,r.registered_at,r.status as reg_status FROM workshops w JOIN registrations r ON w.id=r.workshop_id WHERE r.user_id=? AND r.status="registered" ORDER BY w.date ASC');
$st->bind_param('i',$uid); $st->execute();
$res = $st->get_result(); $list = [];
while ($row = $res->fetch_assoc()) $list[] = $row;
echo json_encode(['success'=>true,'workshops'=>$list]);
$st->close(); $conn->close();
