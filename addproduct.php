<?php
require("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST["ProductName"];
    $productDescription = $_POST["ProductDescription"];
    $productPrice = $_POST["ProductPrice"];
    $productCategory = $_POST["ProductCategory"];
    $reorderQuantity = $_POST["ReorderQuantity"];
    $invenID = $_POST["invenID"];
    $productImage = "";

    // การอัปโหลดไฟล์รูปภาพ
    if (isset($_FILES["ProductImageFile"]) && $_FILES["ProductImageFile"]["error"] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["ProductImageFile"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // ตรวจสอบว่าภาพเป็นไฟล์รูปภาพจริงหรือไม่
        $check = getimagesize($_FILES["ProductImageFile"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["ProductImageFile"]["tmp_name"], $target_file)) {
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

    // เพิ่มข้อมูลลงในฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO product (ProductName, ProductDescription, ProductPrice, ProductCategory, ReorderQuantity, invenID, ProductImage) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsiss", $productName, $productDescription, $productPrice, $productCategory, $reorderQuantity, $invenID, $productImage);

    if ($stmt->execute()) {
        $response["status"] = "success";
    }

    $stmt->close();
    $conn->close();

    header("Location: product.php?status=success");

    exit();
}
