<?php
// เชื่อมต่อกับฐานข้อมูล
require('connect.php');

// ตรวจสอบว่ามี CustomerID ที่ส่งมาหรือไม่
if (isset($_GET['CustomerID'])) {
    $customerId = $_GET['CustomerID'];

    // ดึงข้อมูลลูกค้าจากฐานข้อมูล
    $sql = "SELECT * FROM customer WHERE CustomerID='$customerId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}