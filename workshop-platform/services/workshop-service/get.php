<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once 'db.php';
$d = json_decode(file_get_contents('php://input'), true);
$uid = intval($d['user_id'] ?? 0);
$conn = getDB();
$sql = 'SELECT w.*,
    (SELECT COUNT(*) FROM registrations r WHERE r.workshop_id=w.id AND r.status="registered") as registered_count,
    IF(?>0,(SELECT COUNT(*) FROM registrations r2 WHERE r2.workshop_id=w.id AND r2.user_id=? AND r2.status="registered"),0) as is_registered
    FROM workshops w WHERE w.status="active" ORDER BY w.date ASC';
$st = $conn->prepare($sql);
$st->bind_param('ii', $uid, $uid); $st->execute();
$res = $st->get_result(); $list = [];
while ($row = $res->fetch_assoc()) {
    $row['is_registered'] = (bool)$row['is_registered'];
    $row['available'] = $row['capacity'] - $row['registered_count'];
    $list[] = $row;
}
echo json_encode(['success'=>true,'workshops'=>$list]);
$st->close(); $conn->close();
