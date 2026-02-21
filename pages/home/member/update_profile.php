<?php
session_start();
require __DIR__ . "/../../../php/config.php";

if (empty($_SESSION['member_login'])) {
  header("Location: /aep/index.php?page=member_login");
  exit();
}

$user_id = (int)($_SESSION['member_id'] ?? 0);

$user_name    = trim($_POST['user_name'] ?? '');
$user_phone   = trim($_POST['user_phone'] ?? '');
$user_address = trim($_POST['user_address'] ?? '');
$report_detail = trim($_POST['report_detail'] ?? ''); // ✅ เพิ่ม

// บังคับกรอกเฉพาะ 3 ช่องหลัก (report_detail ปล่อยว่างได้)
if ($user_id <= 0 || $user_name === '' || $user_phone === '' || $user_address === '') {
  $_SESSION['profile_error'] = "กรุณากรอกข้อมูลให้ครบ";
  header("Location: /aep/index.php?page=home");
  exit();
}

// กันยาวเกิน
if (mb_strlen($user_name) > 100)    $user_name = mb_substr($user_name, 0, 100);
if (mb_strlen($user_phone) > 20)   $user_phone = mb_substr($user_phone, 0, 20);
if (mb_strlen($user_address) > 255) $user_address = mb_substr($user_address, 0, 255);
if (mb_strlen($report_detail) > 500) $report_detail = mb_substr($report_detail, 0, 500); // ✅ เพิ่ม

$stmt = $conn->prepare("
  UPDATE users
  SET user_name = ?, user_phone = ?, user_address = ?, report_detail = ?
  WHERE user_id = ?
  LIMIT 1
");
$stmt->bind_param("ssssi", $user_name, $user_phone, $user_address, $report_detail, $user_id);
$stmt->execute();

$_SESSION['profile_msg'] = "บันทึกข้อมูลเรียบร้อย ✅";
header("Location: /aep/index.php?page=home");
exit();

