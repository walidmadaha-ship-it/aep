<?php
session_start();
require __DIR__ . "/../../php/config.php";

if (empty($_SESSION['admin_login'])) {
  http_response_code(403);
  exit('No permission');
}

$req_id = (int)($_POST['req_id'] ?? 0);
if ($req_id <= 0) {
  $_SESSION['status_msg'] = 'ข้อมูลไม่ถูกต้อง';
  header("Location: /aep/index.php?page=status");
  exit();
}

$stmt = $conn->prepare("DELETE FROM service_requests WHERE req_id = ? LIMIT 1");
$stmt->bind_param("i", $req_id);
$stmt->execute();

$_SESSION['status_msg'] = ($stmt->affected_rows > 0)
  ? 'ลบคำขอเรียบร้อยแล้ว 🗑️'
  : 'ไม่พบคำขอ หรือถูกลบไปแล้ว';

$stmt->close();

header("Location: /aep/index.php?page=status");
exit();
