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
$st = $conn->prepare('SELECT capacity,(SELECT COUNT(*) FROM registrations WHERE workshop_id=? AND status="registered") as cnt FROM workshops WHERE id=? AND status="active"');
$st->bind_param('ii',$wid,$wid); $st->execute();
$w = $st->get_result()->fetch_assoc(); $st->close();
if (!$w) { echo json_encode(['success'=>false,'message'=>'Workshop not found']); $conn->close(); exit; }
if ($w['cnt'] >= $w['capacity']) { echo json_encode(['success'=>false,'message'=>'Workshop is full']); $conn->close(); exit; }
$st = $conn->prepare('SELECT id,status FROM registrations WHERE user_id=? AND workshop_id=?');
$st->bind_param('ii',$uid,$wid); $st->execute();
$ex = $st->get_result()->fetch_assoc(); $st->close();
if ($ex) {
    if ($ex['status']==='registered') { echo json_encode(['success'=>false,'message'=>'Already registered']); $conn->close(); exit; }
    $st = $conn->prepare('UPDATE registrations SET status="registered",registered_at=NOW() WHERE id=?');
    $st->bind_param('i',$ex['id']);
} else {
    $st = $conn->prepare('INSERT INTO registrations (user_id,workshop_id) VALUES (?,?)');
    $st->bind_param('ii',$uid,$wid);
}
if ($st->execute()) { echo json_encode(['success'=>true,'message'=>'Registered successfully']); }
else { echo json_encode(['success'=>false,'message'=>'Registration failed']); }
$st->close(); $conn->close();
