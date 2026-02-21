<?php
session_start();

// ลบ session ทั้งหมด
session_unset();
session_destroy();

// กลับหน้า Home
header("Location: /aep/index.php?page=home");
exit();
