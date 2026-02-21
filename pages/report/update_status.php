<?php
session_start();
require __DIR__ . "/../../php/config.php";

if (empty($_SESSION['admin_login'])) {
  http_response_code(response_code: 403);
  exit("Forbidden");
}

$report_id = (int)($_POST['report_id'] ?? 0);
$status = $_POST['status'] ?? '';

$allow = ['pending','working','done','cancel'];
if ($report_id <= 0 || !in_array(needle: $status, haystack: $allow, strict: true)) {
  header(header: "Location: ../../index.php?page=report");
  exit();
}

$stmt = $conn->prepare(query: "UPDATE reports SET status = ? WHERE report_id = ?");
$stmt->bind_param(types: "si", var: $status, vars: $report_id);
$stmt->execute();

header(header: "Location: ../../index.php?page=report");
exit();