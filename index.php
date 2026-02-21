<?php
session_start();
    $page = (!isset($_GET["page"])) ? 'home' : $_GET["page"];
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <?php include('master/head.php');?>
</head>

<body>
    <?php include('master/navbar.php');?>
    <div class="container" style="margin-top:20px;">
        <?php include('pages/'.$page.'/view.php')?>
    </div>
</body>

</html>