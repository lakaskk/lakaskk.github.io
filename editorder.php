<?php
// เชื่อมต่อฐานข้อมูล
require('connect.php');

// ตรวจสอบว่ามีการส่งข้อมูลแบบ POST มาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าที่ถูกส่งมาจากแบบฟอร์มแก้ไข
    $OrID = $_POST['OrID'];
    $editProductName = $_POST['editProductName'];
    $editOrQuantity = $_POST['editOrQuantity'];

    // ดึงข้อมูลสินค้าที่เกี่ยวข้องจากฐานข้อมูลเพื่อหาค่าปัจจุบันของ ReorderQuantity
    $productSql = "SELECT ReorderQuantity FROM product WHERE ProductName = '$editProductName'";
    $productResult = $conn->query($productSql);

    if ($productResult->num_rows > 0) {
        $productRow = $productResult->fetch_assoc();
        $currentReorderQuantity = $productRow['ReorderQuantity'];

        // ดึงค่าปัจจุบันของ OrQuantity
        $currentOrQuantitySql = "SELECT OrQuantity FROM ordetail WHERE OrID = $OrID";
        $currentOrQuantityResult = $conn->query($currentOrQuantitySql);

        if ($currentOrQuantityResult->num_rows > 0) {
            $currentOrQuantityRow = $currentOrQuantityResult->fetch_assoc();
            $currentOrQuantity = $currentOrQuantityRow['OrQuantity'];

            // คำนวณหาค่าใหม่ของ ReorderQuantity โดยลบหรือบวกจำนวนสินค้าที่เปลี่ยนแปลงไปจากค่าปัจจุบัน
            $diffOrQuantity = $editOrQuantity - $currentOrQuantity;
            $newReorderQuantity = $currentReorderQuantity - $diffOrQuantity;

            // อัปเดตค่า ReorderQuantity ในฐานข้อมูล product
            $updateProductSql = "UPDATE product SET ReorderQuantity = $newReorderQuantity WHERE ProductName = '$editProductName'";
            $conn->query($updateProductSql);

            // อัปเดตข้อมูลในฐานข้อมูล ordetail
            $updateOrderSql = "UPDATE ordetail SET ProductName = '$editProductName', OrQuantity = '$editOrQuantity' WHERE OrID = $OrID";

            if ($conn->query($updateOrderSql) === TRUE) {
                // ส่งค่าสำเร็จกลับไปยังหน้าแสดงรายการสั่งซื้อพร้อมกับสถานะการแก้ไข
                header("Location: order.php?status=edit_success");
                exit();
            } else {
                // ส่งค่าล้มเหลวกลับไปยังหน้าแสดงรายการสั่งซื้อพร้อมกับสถานะการแก้ไข
                header("Location: order.php?status=edit_failure");
                exit();
            }
        } else {
            // ถ้าไม่พบข้อมูล OrQuantity
            header("Location: order.php?status=edit_failure");
            exit();
        }
    } else {
        // ถ้าไม่พบข้อมูล ReorderQuantity
        header("Location: order.php?status=edit_failure");
        exit();
    }
} else {
    // ถ้าไม่ได้เรียกผ่านแบบฟอร์ม POST ให้เปลี่ยนเส้นทางไปยังหน้าแสดงรายการสั่งซื้อ
    header("Location: order.php");
    exit();
}
