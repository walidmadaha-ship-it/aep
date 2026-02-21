<?php
session_start();
require __DIR__ . "/../../php/config.php";

if (empty($_SESSION['admin_login'])) {
  exit('No permission');
}

$user_id = (int)($_POST['user_id'] ?? 0);
if ($user_id <= 0) {
  $_SESSION['user_msg'] = 'ข้อมูลไม่ถูกต้อง';
  header("Location: /aep/index.php?page=user");
  exit();
}

$stmt = $conn->prepare("DELETE FROM users WHERE user_id = ? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

$_SESSION['user_msg'] = 'ลบสมาชิกเรียบร้อย ✅';
header("Location: /aep/index.php?page=user");
exit();
