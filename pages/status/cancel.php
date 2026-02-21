<?php
session_start();
require __DIR__ . "/../../php/config.php";

if (empty($_SESSION['member_login'])) {
  header("Location: /aep/index.php?page=member_login");
  exit();
}

$user_id = (int) ($_SESSION['member_id'] ?? 0);
$req_id = (int) ($_POST['req_id'] ?? 0);

if ($user_id <= 0 || $req_id <= 0) {
  $_SESSION['status_msg'] = 'ข้อมูลไม่ถูกต้อง';
  header("Location: /aep/index.php?page=status");
  exit();
}

// ยกเลิกได้เฉพาะคำขอของตัวเอง และต้องเป็น pending หรือ working เท่านั้น
$stmt = $conn->prepare("
  UPDATE service_requests
  SET status = 'cancel'
  WHERE req_id = ?
    AND user_id = ?
    AND (status = 'pending' OR status = 'working')
  LIMIT 1
");
$stmt->bind_param("ii", $req_id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
  $_SESSION['status_msg'] = 'ยกเลิกคำขอเรียบร้อยแล้ว ✅';
} else {
  $_SESSION['status_msg'] = 'ยกเลิกไม่ได้ (อาจไม่ใช่คำขอของคุณ หรือถูกดำเนินการแล้ว)';
}

$stmt->close();

header("Location: /aep/index.php?page=status");
exit();
