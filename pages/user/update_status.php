<?php
session_start();
require __DIR__ . "/../../php/config.php";

if (empty($_SESSION['admin_login'])) {
  http_response_code(403);
  exit('No permission');
}

$user_id = (int) ($_POST['user_id'] ?? 0);
$status = $_POST['service_status'] ?? 'none';

$allow = ['none', 'pending', 'approved'];
if ($user_id <= 0 || !in_array($status, $allow, true)) {
  $_SESSION['user_msg'] = 'ข้อมูลไม่ถูกต้อง';
  header("Location: /aep/index.php?page=user");
  exit();
}

$conn->begin_transaction();

try {
  // 1. Update User Status
  $stmt = $conn->prepare("UPDATE users SET service_status = ? WHERE user_id = ? LIMIT 1");
  $stmt->bind_param("si", $status, $user_id);
  $stmt->execute();
  $stmt->close();

  // 2. Synchronize Service Request Status
  // If Admin approves user -> Set latest request to 'working' (Checked/Processing)
  // If Admin sets to pending -> Set latest request to 'pending' (Wait for check)
  // If Admin sets to none -> Do nothing (retain current status)

  $req_status = null;
  if ($status === 'approved') {
    $req_status = 'working';
  } elseif ($status === 'pending') {
    $req_status = 'pending';
  }

  if ($req_status) {
    // Find latest active request for this user
    $stmt_req = $conn->prepare("
            UPDATE service_requests 
            SET status = ? 
            WHERE user_id = ? 
            ORDER BY req_id DESC 
            LIMIT 1
        ");
    $stmt_req->bind_param("si", $req_status, $user_id);
    $stmt_req->execute();
    $stmt_req->close();
  }

  $conn->commit();
  $_SESSION['user_msg'] = 'อัปเดตสถานะเรียบร้อย ✅ (ซิงค์สถานะคำขอแล้ว)';

} catch (Exception $e) {
  $conn->rollback();
  $_SESSION['user_msg'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
}

header("Location: /aep/index.php?page=user");
exit();
