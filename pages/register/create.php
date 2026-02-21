<?php
session_start();
require __DIR__ . "/../../php/config.php";

$member_username = trim($_POST['member_username'] ?? '');
$password        = $_POST['password'] ?? '';

$user_name       = trim($_POST['user_name'] ?? '');
$user_phone      = trim($_POST['user_phone'] ?? '');
$user_address    = trim($_POST['user_address'] ?? '');
$report_detail   = trim($_POST['report_detail'] ?? '');

if ($member_username === '' || $password === '' || $user_name === '' || $user_phone === '') {
  $_SESSION['reg_msg'] = "กรุณากรอกข้อมูลให้ครบ";
  header("Location: ../../index.php?page=register");
  exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);

// ✅ INSERT เฉพาะคอลัมน์ที่มีอยู่จริง
$sql = "INSERT INTO users
        (member_username, member_password_hash, user_name, user_phone, user_address, report_detail)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssss",
    $member_username,
    $hash,
    $user_name,
    $user_phone,
    $user_address,
    $report_detail
);

if ($stmt->execute()) {
  // ส่งข้อความไปแสดงที่หน้า login
  $_SESSION['member_error'] = "สมัครสมาชิกสำเร็จ ✅ กรุณาเข้าสู่ระบบ";
  header("Location: ../../index.php?page=member_login");
  exit();
} else {
  $_SESSION['reg_msg'] = "สมัครไม่สำเร็จ: " . $conn->error;
  header("Location: ../../index.php?page=register");
  exit();
}

