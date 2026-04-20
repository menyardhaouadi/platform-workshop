<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once 'db.php';
$d = json_decode(file_get_contents('php://input'), true);
$id = intval($d['id'] ?? 0); $reply = trim($d['reply'] ?? '');
if (!$id || !$reply) { echo json_encode(['success'=>false,'message'=>'ID and reply required']); exit; }
$conn = getDB();
$st = $conn->prepare('UPDATE messages SET reply=?,replied_at=NOW(),is_read=1 WHERE id=?');
$st->bind_param('si',$reply,$id); $st->execute();
if ($st->affected_rows > 0) { echo json_encode(['success'=>true,'message'=>'Reply sent']); }
else { echo json_encode(['success'=>false,'message'=>'Message not found']); }
$st->close(); $conn->close();
