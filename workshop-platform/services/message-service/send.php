<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once 'db.php';
$d = json_decode(file_get_contents('php://input'), true);
$sid = intval($d['sender_id'] ?? 0);
$subject = trim($d['subject'] ?? '');
$body = trim($d['body'] ?? '');
if (!$sid || !$subject || !$body) { echo json_encode(['success'=>false,'message'=>'All fields required']); exit; }
$conn = getDB();
$st = $conn->prepare('INSERT INTO messages (sender_id,subject,body) VALUES (?,?,?)');
$st->bind_param('iss',$sid,$subject,$body);
if ($st->execute()) { echo json_encode(['success'=>true,'message'=>'Message sent']); }
else { echo json_encode(['success'=>false,'message'=>'Send failed']); }
$st->close(); $conn->close();
