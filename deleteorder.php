<?php
require('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderId = $_POST['OrID'];

    // ดึงข้อมูล OrQuantity และ ProductName ก่อนลบข้อมูลใน ordetail
    $orQuantitySql = "SELECT ProductName, OrQuantity FROM ordetail WHERE OrID = '$orderId'";
    $orQuantityResult = $conn->query($orQuantitySql);

    if ($orQuantityResult->num_rows > 0) {
        while ($row = $orQuantityResult->fetch_assoc()) {
            $productName = $row['ProductName'];
            $orQuantity = $row['OrQuantity'];

            // อัปเดตค่า ReorderQuantity ของสินค้า
            $productSql = "UPDATE product SET ReorderQuantity = ReorderQuantity + $orQuantity WHERE ProductName = '$productName'";
            $conn->query($productSql);
        }

        // ลบข้อมูลใน ordetail ตาม OrID
        $sql = "DELETE FROM ordetail WHERE OrID = '$orderId'";
        
        if ($conn->query($sql) === TRUE) {
            // ส่งกลับไปยังหน้าหลักพร้อมกับสถานะการดำเนินการสำเร็จ
            header("Location: order.php?status=delete_success");
        } else {
            // ส่งกลับไปยังหน้าหลักพร้อมกับสถานะการดำเนินการล้มเหลว
            header("Location: order.php?status=delete_failure");
        }
    } else {
        // ส่งกลับไปยังหน้าหลักพร้อมกับสถานะการดำเนินการล้มเหลว เนื่องจากไม่พบข้อมูล OrID
        header("Location: order.php?status=delete_failure");
    }

    $conn->close();
    exit();
}
?>
