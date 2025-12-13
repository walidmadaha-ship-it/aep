<?php
header("Content-Type: application/json");

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "plumbing");

$conn->set_charset("utf8");