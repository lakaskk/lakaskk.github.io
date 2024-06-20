<?php
require("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productID = $_POST["edit_product_id"];
    $productName = $_POST["edit_ProductName"];
    $productDescription = $_POST["edit_ProductDescription"];
    $productPrice = $_POST["edit_ProductPrice"];
    $productCategory = $_POST["edit_ProductCategory"];
    $reorderQuantity = $_POST["edit_ReorderQuantity"];
    $invenID = $_POST["edit_invenID"];
    $productImage = "";

    // การอัปโหลดไฟล์รูปภาพใหม่ถ้ามี
    if (isset($_FILES["edit_ProductImageFile"]) && $_FILES["edit_ProductImageFile"]["error"] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["edit_ProductImageFile"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // ตรวจสอบว่าภาพเป็นไฟล์รูปภาพจริงหรือไม่
        $check = getimagesize($_FILES["edit_ProductImageFile"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["edit_ProductImageFile"]["tmp_name"], $target_file)) {
                $productImage = $target_file;
            } else {
                echo "มีข้อผิดพลาดในการอัปโหลดไฟล์ของคุณ";
                exit();
            }
        } else {
            echo "ไฟล์ไม่ใช่รูปภาพ";
            exit();
        }
    }

    // อัปเดตข้อมูลสินค้า
    if (!empty($productImage)) {
        $stmt = $conn->prepare("UPDATE product SET ProductName=?, ProductDescription=?, ProductPrice=?, ProductCategory=?, ReorderQuantity=?, invenID=?, ProductImage=? WHERE productID=?");
        $stmt->bind_param("ssdsissi", $productName, $productDescription, $productPrice, $productCategory, $reorderQuantity, $invenID, $productImage, $productID);
    } else {
        $stmt = $conn->prepare("UPDATE product SET ProductName=?, ProductDescription=?, ProductPrice=?, ProductCategory=?, ReorderQuantity=?, invenID=? WHERE productID=?");
        $stmt->bind_param("ssdsisi", $productName, $productDescription, $productPrice, $productCategory, $reorderQuantity, $invenID, $productID);
    }

    if ($stmt->execute()) {
        echo "อัปเดตข้อมูลสำเร็จ";
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: product.php?status=edit_success");
    exit();
}
