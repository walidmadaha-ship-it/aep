<?php
// config.php

$conn = new mysqli(hostname: "localhost", username: "root", password: "", database: "aep1");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ");
}

// ตั้งค่า charset
$conn->set_charset(charset: "utf8mb4");
