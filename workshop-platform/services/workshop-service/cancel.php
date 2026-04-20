<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once 'db.php';
$d = json_decode(file_get_contents('php://input'), true);
$uid = intval($d['user_id'] ?? 0); $wid = intval($d['workshop_id'] ?? 0);
if (!$uid || !$wid) { echo json_encode(['success'=>false,'message'=>'Missing IDs']); exit; }
$conn = getDB();
$st = $conn->prepare('UPDATE registrations SET status="cancelled" WHERE user_id=? AND workshop_id=? AND status="registered"');
$st->bind_param('ii',$uid,$wid); $st->execute();
if ($st->affected_rows > 0) { echo json_encode(['success'=>true,'message'=>'Cancelled']); }
else { echo json_encode(['success'=>false,'message'=>'No active registration']); }
$st->close(); $conn->close();
