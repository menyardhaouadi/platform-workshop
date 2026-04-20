<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once 'db.php';
$d = json_decode(file_get_contents('php://input'), true);
$id = intval($d['id'] ?? 0);
$title = trim($d['title'] ?? '');
$desc = trim($d['description'] ?? '');
$instructor = trim($d['instructor'] ?? '');
$date = $d['date'] ?? ''; $time = $d['time'] ?? '';
$duration = intval($d['duration'] ?? 60); $capacity = intval($d['capacity'] ?? 30);
$location = trim($d['location'] ?? ''); $image = trim($d['image'] ?? '');
if (!$title || !$date || !$time) { echo json_encode(['success'=>false,'message'=>'Title, date and time required']); exit; }
$conn = getDB();
if ($id > 0) {
    $st = $conn->prepare('UPDATE workshops SET title=?,description=?,instructor=?,date=?,time=?,duration=?,capacity=?,location=?,image=? WHERE id=?');
    $st->bind_param('sssssiiisi',$title,$desc,$instructor,$date,$time,$duration,$capacity,$location,$image,$id);
    $msg = 'Workshop updated';
} else {
    $st = $conn->prepare('INSERT INTO workshops (title,description,instructor,date,time,duration,capacity,location,image) VALUES (?,?,?,?,?,?,?,?,?)');
    $st->bind_param('sssssiiis',$title,$desc,$instructor,$date,$time,$duration,$capacity,$location,$image);
    $msg = 'Workshop created';
}
if ($st->execute()) { echo json_encode(['success'=>true,'message'=>$msg,'id'=>($id>0?$id:$conn->insert_id)]); }
else { echo json_encode(['success'=>false,'message'=>'Operation failed']); }
$st->close(); $conn->close();
