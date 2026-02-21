<?php
session_start();
require __DIR__ . "/../../php/config.php";

if (empty($_SESSION['admin_login'])) {
  http_response_code(403);
  exit("Forbidden");
}

if (($_SESSION['admin_role'] ?? '') !== 'superadmin') {
  $_SESSION['admin_msg'] = "คุณไม่มีสิทธิ์ลบแอดมิน";
  header("Location: ../../index.php?page=admins");
  exit();
}

$id = (int)($_POST['id'] ?? 0);
$self = (int)($_SESSION['admin_id'] ?? 0);

if ($id <= 0) {
  $_SESSION['admin_msg'] = "ข้อมูลไม่ถูกต้อง";
  header("Location: ../../index.php?page=admins");
  exit();
}

if ($id === $self) {
  $_SESSION['admin_msg'] = "ไม่อนุญาตให้ลบตัวเอง";
  header("Location: ../../index.php?page=admins");
  exit();
}

$stmt = $conn->prepare("DELETE FROM admins WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$_SESSION['admin_msg'] = "ลบแอดมินแล้ว ✅";
header("Location: ../../index.php?page=admins");
exit();
