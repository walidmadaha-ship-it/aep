<?php
session_start();
require __DIR__ . "/../../php/config.php";

if (empty($_SESSION['admin_login'])) {
  http_response_code(403);
  exit("Forbidden");
}

if (($_SESSION['admin_role'] ?? '') !== 'superadmin') {
  $_SESSION['admin_msg'] = "คุณไม่มีสิทธิ์เพิ่มแอดมิน (ต้องเป็น superadmin)";
  header("Location: ../../index.php?page=admins");
  exit();
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'admin';

if ($username === '' || $password === '') {
  $_SESSION['admin_msg'] = "กรุณากรอกข้อมูลให้ครบ";
  header("Location: ../../index.php?page=admins");
  exit();
}

if (!in_array($role, ['admin','superadmin'], true)) $role = 'admin';

$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (username, password_hash, role, is_active) VALUES (?, ?, ?, 1)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $hash, $role);

if ($stmt->execute()) {
  $_SESSION['admin_msg'] = "เพิ่มแอดมินสำเร็จ ✅";
} else {
  $_SESSION['admin_msg'] = "เพิ่มไม่สำเร็จ: " . $conn->error;
}

header("Location: ../../index.php?page=admins");
exit();
