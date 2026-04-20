<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once 'db.php';
$d = json_decode(file_get_contents('php://input'), true);
$id = intval($d['id'] ?? 0);
if (!$id) { echo json_encode(['success'=>false,'message'=>'ID required']); exit; }
$conn = getDB();
$st = $conn->prepare('DELETE FROM workshops WHERE id=?');
$st->bind_param('i',$id); $st->execute();
if ($st->affected_rows > 0) { echo json_encode(['success'=>true,'message'=>'Deleted']); }
else { echo json_encode(['success'=>false,'message'=>'Not found']); }
$st->close(); $conn->close();
