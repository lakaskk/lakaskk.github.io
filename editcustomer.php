<?php
// เชื่อมต่อกับฐานข้อมูล
require('connect.php');

// ตรวจสอบว่ามีข้อมูลที่ส่งมาจากแบบฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ตรวจสอบว่ามีข้อมูลลูกค้าที่ต้องการแก้ไขหรือไม่
    if (isset($_POST['edit_CustomerID'])) {
        $customerId = $_POST['edit_CustomerID'];
        $customerName = $_POST['edit_CustomerName'];
        $customerAddress = $_POST['edit_CustomerAddress'];
        $customerTel = $_POST['edit_CustomerTel'];

        // อัปเดตข้อมูลลูกค้าในฐานข้อมูล
        $sql = "UPDATE customer SET CustomerName='$customerName', CustomerAddress='$customerAddress', CustomerTel='$customerTel' WHERE CustomerID='$customerId'";

        if ($conn->query($sql) === TRUE) {
            // ส่งกลับสถานะสำเร็จถ้าอัปเดตข้อมูลสำเร็จ
            header("Location: customer.php?status=edit_success");
            exit();
        } else {
            // ส่งกลับสถานะล้มเหลวถ้าเกิดข้อผิดพลาดในการอัปเดตข้อมูล
            header("Location: customer.php?status=edit_failure");
            exit();
        }
    } else {
        // ส่งกลับถ้าไม่มีข้อมูลลูกค้าที่ต้องการแก้ไข
        header("Location: customer.php");
        exit();
    }
}
?>