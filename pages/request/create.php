<?php
session_start();
require __DIR__ . "/../../php/config.php";

if (empty($_SESSION['member_login'])) {
  header("Location: ../../index.php?page=member_login");
  exit();
}

$user_id = (int)($_SESSION['member_id'] ?? 0);

$device_type = trim($_POST['device_type'] ?? '');
$qty = (int)($_POST['qty'] ?? 1);
$location = trim($_POST['location'] ?? '');

$duration_value = (int)($_POST['duration_value'] ?? 1);
$duration_unit  = $_POST['duration_unit'] ?? 'hour';

$note = trim($_POST['note'] ?? '');
$start_time = $_POST['start_time'] ?? '';

if ($user_id <= 0 || $device_type === '' || $location === '') {
  $_SESSION['req_error'] = "กรุณากรอกข้อมูลให้ครบ";
  header("Location: ../../index.php?page=request");
  exit();
}

if ($qty < 1) $qty = 1;
if ($duration_value < 1) $duration_value = 1;

if (!in_array($duration_unit, ['hour','day'], true)) {
  $duration_unit = 'hour';
}

// datetime-local -> DATETIME
$start_dt = null;
if (!empty($start_time)) {
  $start_dt = str_replace('T', ' ', $start_time);
  if (strlen($start_dt) === 16) $start_dt .= ":00";
}

// ✅ สถานะเริ่มต้น
$status = 'pending';

try {
  $conn->begin_transaction();

  // 1) INSERT ลง service_requests (ตารางสถานะใช้งาน)
  $stmt = $conn->prepare("
    INSERT INTO service_requests
      (user_id, device_type, qty, location, start_time, duration_value, duration_unit, note, status)
    VALUES
      (?, ?, ?, ?, ?, ?, ?, ?, ?)
  ");
  $stmt->bind_param(
    "isississs",
    $user_id,
    $device_type,
    $qty,
    $location,
    $start_dt,
    $duration_value,
    $duration_unit,
    $note,
    $status
  );
  $stmt->execute();

  // req_id ที่เพิ่งสร้าง
  $new_req_id = $conn->insert_id;
  $stmt->close();

  // 2) ดึงชื่อ/เบอร์จาก users เพื่อเก็บเป็น snapshot
  $u = $conn->prepare("SELECT user_name, user_phone FROM users WHERE user_id=? LIMIT 1");
  $u->bind_param("i", $user_id);
  $u->execute();
  $uu = $u->get_result()->fetch_assoc();
  $u->close();

  $hist_name  = $uu['user_name'] ?? null;
  $hist_phone = $uu['user_phone'] ?? null;

  // 3) INSERT ลง request_history (ตารางประวัติถาวร)
  $h = $conn->prepare("
    INSERT INTO request_history
      (req_id, user_id, user_name, user_phone, device_type, qty, location, start_time,
       duration_value, duration_unit, note, status)
    VALUES
      (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  ");
  $h->bind_param(
    "iisssississs",
    $new_req_id,
    $user_id,
    $hist_name,
    $hist_phone,
    $device_type,
    $qty,
    $location,
    $start_dt,
    $duration_value,
    $duration_unit,
    $note,
    $status
  );
  $h->execute();
  $h->close();

  // 4) อัปเดต users.service_status ให้หน้า member ขึ้น "รอตรวจสอบ"
  $stmt2 = $conn->prepare("
    UPDATE users
    SET service_status = 'pending'
    WHERE user_id = ?
    LIMIT 1
  ");
  $stmt2->bind_param("i", $user_id);
  $stmt2->execute();
  $stmt2->close();

  $conn->commit();

  $_SESSION['req_success'] = "ส่งคำขอเรียบร้อยแล้ว ✅ (รอตรวจสอบ)";
  header("Location: ../../index.php?page=status");
  exit();

} catch (Throwable $e) {
  $conn->rollback();
  $_SESSION['req_error'] = "ส่งคำขอไม่สำเร็จ: " . $e->getMessage();
  header("Location: ../../index.php?page=request");
  exit();
}
