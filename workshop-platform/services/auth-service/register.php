<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once 'db.php';
$d = json_decode(file_get_contents('php://input'), true);
$name = trim($d['name'] ?? '');
$email = trim($d['email'] ?? '');
$password = $d['password'] ?? '';
if (!$name || !$email || !$password) { echo json_encode(['success'=>false,'message'=>'All fields required']); exit; }
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { echo json_encode(['success'=>false,'message'=>'Invalid email']); exit; }
if (strlen($password) < 6) { echo json_encode(['success'=>false,'message'=>'Password min 6 chars']); exit; }
$conn = getDB();
$st = $conn->prepare('SELECT id FROM users WHERE email=?');
$st->bind_param('s', $email); $st->execute(); $st->store_result();
if ($st->num_rows > 0) { echo json_encode(['success'=>false,'message'=>'Email already exists']); exit; }
$st->close();
$hash = password_hash($password, PASSWORD_BCRYPT);
$role = 'student';
$st = $conn->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)');
$st->bind_param('ssss', $name, $email, $hash, $role);
if ($st->execute()) {
    $id = $conn->insert_id;
    $_SESSION['user'] = ['id'=>$id,'name'=>$name,'email'=>$email,'role'=>$role];
    echo json_encode(['success'=>true,'user'=>['id'=>$id,'name'=>$name,'email'=>$email,'role'=>$role]]);
} else {
    echo json_encode(['success'=>false,'message'=>'Registration failed']);
}
$st->close(); $conn->close();
