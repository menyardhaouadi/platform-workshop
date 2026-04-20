<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once 'db.php';
$conn = getDB();
$students  = $conn->query('SELECT COUNT(*) c FROM users WHERE role="student"')->fetch_assoc()['c'];
$workshops = $conn->query('SELECT COUNT(*) c FROM workshops WHERE status="active"')->fetch_assoc()['c'];
$regs      = $conn->query('SELECT COUNT(*) c FROM registrations WHERE status="registered"')->fetch_assoc()['c'];
$unread    = $conn->query('SELECT COUNT(*) c FROM messages WHERE is_read=0')->fetch_assoc()['c'];
$wstats = [];
$res = $conn->query('SELECT w.id,w.title,w.capacity,COUNT(r.id) as registered FROM workshops w LEFT JOIN registrations r ON w.id=r.workshop_id AND r.status="registered" GROUP BY w.id ORDER BY registered DESC');
while ($row = $res->fetch_assoc()) $wstats[] = $row;
echo json_encode(['success'=>true,'students'=>(int)$students,'workshops'=>(int)$workshops,'registrations'=>(int)$regs,'unread'=>(int)$unread,'workshop_stats'=>$wstats]);
$conn->close();
