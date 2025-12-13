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

    function select_user($conn) {

        $sql = "SELECT user_id, user_name, user_address, user_phone FROM users ORDER BY user_id DESC";
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

    function insert_user($conn) {
        $sql = "INSERT INTO users (user_name, user_address, user_phone)
                VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sss",
            $_POST['user_name'],
            $_POST['user_address'],
            $_POST['user_phone']
        );

        $stmt->execute();

        echo json_encode(["status" => "success"]);
    }

    function update_user($conn) {
        $sql = "UPDATE users 
                SET user_name=?, user_address=?, user_phone=?
                WHERE user_id=?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssi",
            $_POST['user_name'],
            $_POST['user_address'],
            $_POST['user_phone'],
            $_POST['user_id']
        );

        $stmt->execute();

        echo json_encode(["status" => "success"]);
    }

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

    function delete_user($conn) {
        $sql = "DELETE FROM users WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['user_id']);

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



