<?php
require('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $productNames = $_POST['ProductName'];
  $quantities = $_POST['OrQuantity'];
  $orderDate = $_POST['OrDate'];
  $customerName = $_POST['CustomerName'];

  $success = true;

  for ($i = 0; $i < count($productNames); $i++) {
    $productName = $productNames[$i];
    $quantity = $quantities[$i];

    // ดึง ProductPrice จากตาราง product
    $sql = "SELECT ProductPrice FROM product WHERE ProductName ='$productName'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $product = $result->fetch_assoc();
      $productPrice = $product['ProductPrice'];
    } else {
      // หากไม่พบ ProductName ในตาราง product ให้ตั้งค่า $success เป็น false และออกจากลูป
      $success = false;
      break;
    }

    // คำนวณ totalprice
    $totalprice = $productPrice * $quantity;

    // Insert order detail into ordetail table
    $insertsql = "INSERT INTO ordetail (ProductImage, ProductName, OrQuantity, ProductPrice, OrDate, CustomerName) 
                  VALUES ('$productImage', '$productName', '$quantity', '$totalprice', '$orderDate', '$customerName')";
    if ($conn->query($insertsql) === TRUE) {
      // Update product quantity in Product table
      $updateSql = "UPDATE Product SET ReorderQuantity = ReorderQuantity - $quantity WHERE ProductName = '$productName'";
      if ($conn->query($updateSql) !== TRUE) {
        $success = false;
        break;
      }
    } else {
      $success = false;
      break;
    }
  }

  if ($success) {
    // redirect กลับไปยังหน้าหลักพร้อมกับ parameter ที่บ่งบอกว่าการดำเนินการสำเร็จ
    header("Location: order.php?status=success");
  } else {
    // redirect กลับไปยังหน้าหลักพร้อมกับ parameter ที่บ่งบอกว่าการดำเนินการล้มเหลว
    header("Location: order.php?status=failure");
  }
  exit();
}

$conn->close();
