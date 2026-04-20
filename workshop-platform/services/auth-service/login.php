<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once 'db.php';
$d = json_decode(file_get_contents('php://input'), true);
$email = trim($d['email'] ?? '');
$password = $d['password'] ?? '';
if (!$email || !$password) { echo json_encode(['success'=>false,'message'=>'Email and password required']); exit; }
$conn = getDB();
$st = $conn->prepare('SELECT id,name,email,password,role FROM users WHERE email=?');
$st->bind_param('s', $email); $st->execute();
$r = $st->get_result(); $user = $r->fetch_assoc(); $st->close();
if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode(['success'=>false,'message'=>'Invalid email or password']); $conn->close(); exit;
}
$_SESSION['user'] = ['id'=>$user['id'],'name'=>$user['name'],'email'=>$user['email'],'role'=>$user['role']];
echo json_encode(['success'=>true,'user'=>['id'=>$user['id'],'name'=>$user['name'],'email'=>$user['email'],'role'=>$user['role']]]);
$conn->close();
