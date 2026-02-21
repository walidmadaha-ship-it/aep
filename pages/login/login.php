<?php
session_start();
require __DIR__ . "/../../php/config.php"; // เชื่อม DB (จาก pages/login ไปหา php/config.php)

// รับค่าจากฟอร์ม
$username = trim($_POST['user_admin'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo json_encode(['status' => 'error', 'message' => 'กรุณากรอก Username และ Password']);
    exit();
  }
  $_SESSION['login_error'] = "กรุณากรอก Username และ Password";
  header("Location: ../../index.php?page=home");
  exit();
}

// ค้นหา admin ใน DB
$sql = "SELECT id, username, password_hash, role, is_active
        FROM admins
        WHERE username = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// เช็คว่าเจอและ active
if (!$admin || (int) $admin['is_active'] !== 1) {
  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo json_encode(['status' => 'error', 'message' => 'Username หรือ Password ไม่ถูกต้อง']);
    exit();
  }
  $_SESSION['login_error'] = "Username หรือ Password ไม่ถูกต้อง";
  header("Location: ../../index.php?page=home");
  exit();
}

// ตรวจรหัสผ่านแบบ hash
if (!password_verify($password, $admin['password_hash'])) {
  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo json_encode(['status' => 'error', 'message' => 'Username หรือ Password ไม่ถูกต้อง']);
    exit();
  }
  $_SESSION['login_error'] = "Username หรือ Password ไม่ถูกต้อง";
  header("Location: ../../index.php?page=home");
  exit();
}

// สำเร็จ
session_regenerate_id(true);
$_SESSION['admin_login'] = true;
$_SESSION['admin_id'] = (int) $admin['id'];
$_SESSION['admin_name'] = $admin['username'];
$_SESSION['admin_role'] = $admin['role'];

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  echo json_encode(['status' => 'success']);
  exit();
}
header("Location: ../../index.php?page=home");
exit();
