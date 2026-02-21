<?php
session_start();
unset($_SESSION['member_login'], $_SESSION['member_id'], $_SESSION['member_username'], $_SESSION['member_name']);
header("Location: ../../index.php?page=home");
exit();
