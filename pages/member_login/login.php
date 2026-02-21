<?php
session_start();
require __DIR__ . "/../../php/config.php";

$u = trim($_POST['member_username'] ?? '');
$p = $_POST['password'] ?? '';

if ($u === '' || $p === '') {
  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo json_encode(['status' => 'error', 'message' => 'กรุณากรอก Username และ Password']);
    exit();
  }
  $_SESSION['member_error'] = "กรุณากรอก Username และ Password";
  header("Location: ../../index.php?page=member_login");
  exit();
}

$sql = "SELECT user_id, member_username, member_password_hash, user_name
        FROM users
        WHERE member_username = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
if (!$stmt) {
  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    exit();
  }
  $_SESSION['member_error'] = "Database error: " . $conn->error;
  header("Location: ../../index.php?page=member_login");
  exit();
}
$stmt->bind_param("s", $u);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

if (!$user) {
  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo json_encode(['status' => 'error', 'message' => 'Username หรือ Password ไม่ถูกต้อง']);
    exit();
  }
  $_SESSION['member_error'] = "Username หรือ Password ไม่ถูกต้อง";
  header("Location: ../../index.php?page=member_login");
  exit();
}

if (!password_verify($p, $user['member_password_hash'])) {
  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo json_encode(['status' => 'error', 'message' => 'Username หรือ Password ไม่ถูกต้อง']);
    exit();
  }
  $_SESSION['member_error'] = "Username หรือ Password ไม่ถูกต้อง";
  header("Location: ../../index.php?page=member_login");
  exit();
}

// ✅ Login success
session_regenerate_id(true);
$_SESSION['member_login'] = true;
$_SESSION['member_id'] = (int) $user['user_id'];
$_SESSION['member_username'] = $user['member_username'];
$_SESSION['member_name'] = $user['user_name'];

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  echo json_encode(['status' => 'success']);
  exit();
}
header("Location: ../../index.php?page=home");
exit();
