<?php

    include('../../php/config.php');

    $fn = $_POST['fn'] ?? "";

    switch ($fn) {

        case "select_user": select_user($conn); break;
        case "insert_user": insert_user($conn); break;
        case "update_user": update_user($conn); break;
        case "get_user":    get_user($conn);    break;
        case "delete_user": delete_user($conn); break;

        default:
            echo json_encode([
                "status" => "error",
                "message" => "Invalid function"
            ]);
        break;
    }

    $conn->close();

    //================ ฟังก์ชันดึงข้อมูล ===========================

    function select_user($conn) {

        $sql = "SELECT * FROM users ORDER BY user_id DESC";//ดึงจากตาราง
        $result = $conn->query($sql);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        echo json_encode([
            "status" => "success",
            "data" => $users
        ]);
    }

    //================== ฟังก์ชันเพิ่มข้อมูล ===========================

    function insert_user($conn) {
        //บันทึกข้อมูล
        $sql = "INSERT INTO users (user_name, user_address, user_phone)
                VALUES (?, ?, ?)";//เปลี่ยนตามจำนวน

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sss",//เปลี่ยนตามจำนวน
            $_POST['user_name'],//ค่า user_name ที่ส่งมา
            $_POST['user_address'],//ค่า user_address ที่ส่งมา
            $_POST['user_phone']//ค่า user_phone ที่ส่งมา
        );

        $stmt->execute();

        echo json_encode(["status" => "success"]);
    }

    //=================== ฟังก์ชันแก้ไขข้อมูล ==========================

    function update_user($conn) {
        $sql = "UPDATE users 
                SET user_name=?, user_address=?, user_phone=?
                WHERE user_id=?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssi",
            $_POST['user_name'],//ค่า user_name ที่ส่งมา
            $_POST['user_address'],//ค่า user_address ที่ส่งมา
            $_POST['user_phone'],//ค่า user_phone ที่ส่งมา
            $_POST['user_id']//ค่า user_id ที่ส่งมา
        );

        $stmt->execute();

        echo json_encode(["status" => "success"]);
    }

    //================== ฟังก์ชันดึง user มาแก้ไข ==================
    function get_user($conn) {
        $sql = "SELECT * FROM users WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['user_id']);
        $stmt->execute();

        $result = $stmt->get_result();
        echo json_encode([
            "status" => "success",
            "data" => $result->fetch_assoc()
        ]);
    }

    //=================== ฟังก์ชันลบข้อมูล =============================

    function delete_user($conn) {
        $sql = "DELETE FROM users WHERE user_id=?";//ลบในตาราง users
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['user_id']);//ลบในตาราง user_id ที่ส่งมา

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "ไม่สามารถลบข้อมูลได้"
            ]);
        }
    }
    



