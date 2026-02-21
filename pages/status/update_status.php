<?php
session_start();
require __DIR__ . "/../../php/config.php";

if (empty($_SESSION['admin_login'])) {
  header("Location: ../../index.php?page=home");
  exit();
}

$req_id = (int)($_POST['req_id'] ?? 0);
$status = $_POST['status'] ?? '';

$allowed = ['pending','working','done','cancel'];
if ($req_id <= 0 || !in_array($status, $allowed, true)) {
  header("Location: ../../index.php?page=status");
  exit();
}

$stmt = $conn->prepare("
  UPDATE service_requests
  SET status = ?, updated_at = NOW()
  WHERE req_id = ?
");
$stmt->bind_param("si", $status, $req_id);
$stmt->execute();

$_SESSION['status_msg'] = "อัปเดตสถานะเรียบร้อย ✅";
header("Location: ../../index.php?page=status");
exit();
