<?php
session_start();
require __DIR__ . "/../../php/config.php";

// ✅ รองรับได้ทั้ง name/phone และ reporter_name/reporter_phone
$name   = trim($_POST['name'] ?? ($_POST['reporter_name'] ?? ''));
$phone  = trim($_POST['phone'] ?? ($_POST['reporter_phone'] ?? ''));
$room   = trim($_POST['room'] ?? '');
$detail = trim($_POST['detail'] ?? '');

if ($name === '' || $phone === '' || $room === '' || $detail === '') {
  header("Location: ../../index.php?page=home&success=0");
  exit();
}

// ถ้าเป็น member ให้ผูก user_id
$user_id = null;
if (!empty($_SESSION['member_login']) && !empty($_SESSION['member_id'])) {
  $user_id = (int)$_SESSION['member_id'];
}

$stmt = $conn->prepare("
  INSERT INTO reports (user_id, reporter_name, reporter_phone, room, detail)
  VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("issss", $user_id, $name, $phone, $room, $detail);
$stmt->execute();

header("Location: ../../index.php?page=home&success=1");
exit();
