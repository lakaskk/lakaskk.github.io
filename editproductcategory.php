<?php
// เชื่อมต่อกับฐานข้อมูล
require('connect.php');

// ตรวจสอบว่ามีข้อมูลที่ส่งมาจากแบบฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ตรวจสอบว่ามีข้อมูลลูกค้าที่ต้องการแก้ไขหรือไม่
    if (isset($_POST['edit_ProductCategoryID'])) {
        $categoryID = $_POST['edit_ProductCategoryID'];
        $productCategory = $_POST['edit_ProductCategory'];

        // อัปเดตข้อมูลลูกค้าในฐานข้อมูล
        $sql = "UPDATE productcategory SET ProductCategory	='$productCategory' WHERE CategoryID ='$categoryID'";

        if ($conn->query($sql) === TRUE) {
            // ส่งกลับสถานะสำเร็จถ้าอัปเดตข้อมูลสำเร็จ
            header("Location: inventory.php?status=edit_success");
            exit();
        } else {
            // ส่งกลับสถานะล้มเหลวถ้าเกิดข้อผิดพลาดในการอัปเดตข้อมูล
            header("Location: inventory.php?status=edit_failure");
            exit();
        }
    } else {
        // ส่งกลับถ้าไม่มีข้อมูลลูกค้าที่ต้องการแก้ไข
        header("Location: inventory.php");
        exit();
    }
}
?>